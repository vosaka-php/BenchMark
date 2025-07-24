<?php

class CallbackSerializer
{
    /**
     * Serialize callback thành string kèm theo callable thực tế
     * 
     * @param callable $callback
     * @return string
     * @throws InvalidArgumentException
     */
    public static function serialize($callback): string
    {
        // Kiểm tra xem callback có thể gọi được không
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('Provided callback is not callable');
        }
        
        if (is_string($callback)) {
            // Function name hoặc static method
            if (function_exists($callback)) {
                return json_encode([
                    'type' => 'function',
                    'value' => $callback,
                    'callable' => true
                ]);
            } else {
                // Có thể là static method dạng 'Class::method'
                return json_encode([
                    'type' => 'static_method_string',
                    'value' => $callback,
                    'callable' => true
                ]);
            }
        }
        
        if (is_array($callback)) {
            if (count($callback) === 2) {
                [$class, $method] = $callback;
                
                if (is_object($class)) {
                    // Instance method - serialize cả object
                    return json_encode([
                        'type' => 'instance_method',
                        'class' => get_class($class),
                        'method' => $method,
                        'object' => serialize($class),
                        'callable' => is_callable([$class, $method])
                    ]);
                } else {
                    // Static method
                    return json_encode([
                        'type' => 'static_method',
                        'class' => $class,
                        'method' => $method,
                        'callable' => is_callable([$class, $method])
                    ]);
                }
            }
        }
        
        if ($callback instanceof Closure) {
            // Closure hoặc Arrow Function - sử dụng reflection để lấy thông tin
            $reflection = new ReflectionFunction($callback);
            
            // Lấy source code của closure
            $filename = $reflection->getFileName();
            $startLine = $reflection->getStartLine();
            $endLine = $reflection->getEndLine();
            
            // Kiểm tra xem có phải arrow function không
            $isArrowFunction = false;
            $closureSource = '';
            
            if ($filename && $startLine && $endLine) {
                $source = file($filename);
                $closureSource = implode('', array_slice($source, $startLine - 1, $endLine - $startLine + 1));
                $closureSource = trim($closureSource);
                
                // Detect arrow function pattern: fn(...) =>
                if (preg_match('/\bfn\s*\([^)]*\)\s*=>/i', $closureSource)) {
                    $isArrowFunction = true;
                }
            }
            
            $closureData = [
                'type' => $isArrowFunction ? 'arrow_function' : 'closure',
                'callable' => true,
                'use_vars' => $reflection->getStaticVariables(),
                'parameters' => [],
                'is_arrow_function' => $isArrowFunction
            ];
            
            // Lấy thông tin parameters
            foreach ($reflection->getParameters() as $param) {
                $paramInfo = [
                    'name' => $param->getName(),
                    'optional' => $param->isOptional(),
                    'has_default' => $param->isDefaultValueAvailable()
                ];
                
                if ($param->isDefaultValueAvailable()) {
                    $paramInfo['default'] = $param->getDefaultValue();
                }
                
                // Thêm type hint nếu có
                if ($param->hasType()) {
                    $paramInfo['type'] = $param->getType()->getName();
                }
                
                $closureData['parameters'][] = $paramInfo;
            }
            
            if ($filename && $startLine && $endLine) {
                $closureData['source'] = $closureSource;
                $closureData['filename'] = $filename;
                $closureData['start_line'] = $startLine;
                $closureData['end_line'] = $endLine;
            }
            
            // Serialize closure bằng cách lưu trữ serialized closure
            try {
                $closureData['serialized_closure'] = serialize($callback);
            } catch (Exception $e) {
                // Fallback nếu không thể serialize closure
                $closureData['serialized_closure'] = null;
            }
            
            return json_encode($closureData);
        }
        
        // Callback dạng object có method __invoke
        if (is_object($callback) && method_exists($callback, '__invoke')) {
            return json_encode([
                'type' => 'invokable_object',
                'class' => get_class($callback),
                'object' => serialize($callback),
                'callable' => true
            ]);
        }
        
        throw new InvalidArgumentException('Unsupported callback type');
    }
    
    /**
     * Deserialize string thành callback
     * 
     * @param string $serialized
     * @return callable
     * @throws InvalidArgumentException
     */
    public static function deserialize(string $serialized): callable
    {
        $data = json_decode($serialized, true);
        
        if (!$data || !isset($data['type'])) {
            throw new InvalidArgumentException('Invalid serialized callback');
        }
        
        // Kiểm tra xem callback có được đánh dấu là callable không
        if (!isset($data['callable']) || !$data['callable']) {
            throw new InvalidArgumentException('Serialized callback was not callable');
        }
        
        switch ($data['type']) {
            case 'function':
                if (!function_exists($data['value'])) {
                    throw new InvalidArgumentException("Function {$data['value']} does not exist");
                }
                return $data['value'];
                
            case 'static_method_string':
                if (!is_callable($data['value'])) {
                    throw new InvalidArgumentException("Static method {$data['value']} is not callable");
                }
                return $data['value'];
                
            case 'static_method':
                if (!is_callable([$data['class'], $data['method']])) {
                    throw new InvalidArgumentException("Method {$data['class']}::{$data['method']} is not callable");
                }
                return [$data['class'], $data['method']];
                
            case 'instance_method':
                $object = unserialize($data['object']);
                if (!is_callable([$object, $data['method']])) {
                    throw new InvalidArgumentException("Method {$data['class']}::{$data['method']} is not callable");
                }
                return [$object, $data['method']];
                
            case 'invokable_object':
                $object = unserialize($data['object']);
                if (!is_callable($object)) {
                    throw new InvalidArgumentException("Object {$data['class']} is not callable");
                }
                return $object;
                
            case 'closure':
            case 'arrow_function':
                // Thử deserialize closure trước
                if (isset($data['serialized_closure']) && $data['serialized_closure'] !== null) {
                    try {
                        $closure = unserialize($data['serialized_closure']);
                        if ($closure instanceof Closure) {
                            return $closure;
                        }
                    } catch (Exception $e) {
                        // Fallback to eval method
                    }
                }
                
                // Fallback: sử dụng eval (cẩn thận với security)
                if (isset($data['source'])) {
                    $useVars = $data['use_vars'] ?? [];
                    if (!empty($useVars)) {
                        extract($useVars);
                    }
                    
                    // Cảnh báo: Executing arbitrary code - chỉ sử dụng với trusted input
                    $closure = eval("return {$data['source']};");
                    
                    if (!($closure instanceof Closure)) {
                        throw new InvalidArgumentException('Failed to reconstruct ' . ($data['type'] === 'arrow_function' ? 'arrow function' : 'closure'));
                    }
                    
                    return $closure;
                }
                
                throw new InvalidArgumentException('Cannot deserialize ' . ($data['type'] === 'arrow_function' ? 'arrow function' : 'closure') . ': no source available');
                
            default:
                throw new InvalidArgumentException("Unknown callback type: {$data['type']}");
        }
    }
    
    /**
     * Kiểm tra xem callback có thể serialize được không
     * 
     * @param callable $callback
     * @return bool
     */
    public static function canSerialize($callback): bool
    {
        try {
            self::serialize($callback);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Lấy thông tin về callback đã serialize
     * 
     * @param string $serialized
     * @return array
     */
    public static function getInfo(string $serialized): array
    {
        $data = json_decode($serialized, true);
        
        if (!$data || !isset($data['type'])) {
            throw new InvalidArgumentException('Invalid serialized callback');
        }
        
        $info = [
            'type' => $data['type'],
            'callable' => $data['callable'] ?? false
        ];
        
        switch ($data['type']) {
            case 'function':
                $info['function'] = $data['value'];
                break;
                
            case 'static_method_string':
                $info['method'] = $data['value'];
                break;
                
            case 'static_method':
                $info['class'] = $data['class'];
                $info['method'] = $data['method'];
                break;
                
            case 'instance_method':
                $info['class'] = $data['class'];
                $info['method'] = $data['method'];
                break;
                
            case 'invokable_object':
                $info['class'] = $data['class'];
                break;
                
            case 'closure':
            case 'arrow_function':
                $info['parameters'] = $data['parameters'] ?? [];
                $info['use_vars'] = $data['use_vars'] ?? [];
                $info['is_arrow_function'] = $data['is_arrow_function'] ?? false;
                if (isset($data['filename'])) {
                    $info['filename'] = $data['filename'];
                    $info['start_line'] = $data['start_line'];
                    $info['end_line'] = $data['end_line'];
                }
                break;
        }
        
        return $info;
    }
}

// Helper functions
function serializeCallback($callback): string
{
    return CallbackSerializer::serialize($callback);
}

function deserializeCallback(string $serialized): callable
{
    return CallbackSerializer::deserialize($serialized);
}

function getCallbackInfo(string $serialized): array
{
    return CallbackSerializer::getInfo($serialized);
}

// Example usage
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    // Test với function
    function testFunction($x) {
        return $x * 2;
    }
	
	function hello() {
		return "Hello world!";
	}
    
    // Test với static method
    class TestClass {
        public static function staticMethod($x) {
            return $x * 3;
        }
        
        public function instanceMethod($x) {
            return $x * 4;
        }
    }
    
    // Test với invokable object
    class InvokableClass {
        private $multiplier;
        
        public function __construct($multiplier) {
            $this->multiplier = $multiplier;
        }
        
        public function __invoke($x) {
            return $x * $this->multiplier;
        }
    }
    
    // Test với arrow function (PHP 7.4+)
    $arrowFunction = fn($x) => $x * 7;
    
    // Test với closure
    $multiplier = 5;
    $closure = function($x) use ($multiplier) {
        return $x * $multiplier;
    };
    
    echo "Testing improved callback serialization:\n\n";
    
    // Test function
    $serialized = serializeCallback('testFunction');
    echo "Function serialized: " . $serialized . "\n";
    $info = getCallbackInfo($serialized);
    echo "Function info: " . json_encode($info) . "\n";
    $deserialized = deserializeCallback($serialized);
    echo "Function result: " . $deserialized(10) . "\n\n";
    
    // Test static method
    $serialized = serializeCallback([TestClass::class, 'staticMethod']);
    echo "Static method serialized: " . $serialized . "\n";
    $info = getCallbackInfo($serialized);
    echo "Static method info: " . json_encode($info) . "\n";
    $deserialized = deserializeCallback($serialized);
    echo "Static method result: " . $deserialized(10) . "\n\n";
    
    // Test instance method
    $obj = new TestClass();
    $serialized = serializeCallback([$obj, 'instanceMethod']);
    echo "Instance method serialized: " . $serialized . "\n";
    $info = getCallbackInfo($serialized);
    echo "Instance method info: " . json_encode($info) . "\n";
    $deserialized = deserializeCallback($serialized);
    echo "Instance method result: " . $deserialized(10) . "\n\n";
	
	$serialized = serializeCallback('hello');
    echo "Function serialized: " . $serialized . "\n";
    $info = getCallbackInfo($serialized);
    echo "Function info: " . json_encode($info) . "\n";
    $deserialized = deserializeCallback($serialized);
    echo "Function result: " . $deserialized() . "\n\n";
    
    // Test invokable object
    $invokable = new InvokableClass(6);
    $serialized = serializeCallback($invokable);
    echo "Invokable object serialized: " . $serialized . "\n";
    $info = getCallbackInfo($serialized);
    echo "Invokable object info: " . json_encode($info) . "\n";
    $deserialized = deserializeCallback($serialized);
    echo "Invokable object result: " . $deserialized(10) . "\n\n";
    
    // Test arrow function
    try {
        $serialized = serializeCallback($arrowFunction);
        echo "Arrow function serialized: " . $serialized . "\n";
        $info = getCallbackInfo($serialized);
        echo "Arrow function info: " . json_encode($info) . "\n";
        $deserialized = deserializeCallback($serialized);
        echo "Arrow function result: " . $deserialized(10) . "\n\n";
    } catch (Exception $e) {
        echo "Arrow function serialization failed: " . $e->getMessage() . "\n\n";
    }
    
    // Test closure
    try {
        $serialized = serializeCallback($closure);
        echo "Closure serialized: " . $serialized . "\n";
        $info = getCallbackInfo($serialized);
        echo "Closure info: " . json_encode($info) . "\n";
        $deserialized = deserializeCallback($serialized);
        echo "Closure result: " . $deserialized(10) . "\n";
    } catch (Exception $e) {
        echo "Closure serialization failed: " . $e->getMessage() . "\n";
    }
}
?>