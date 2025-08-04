<?php

namespace OpenAPI\Client\Utils;

/**
 * Log levels
 */
class LogLevel
{
    public const ERROR = 0;
    public const WARN = 1;
    public const INFO = 2;
    public const DEBUG = 3;
}

/**
 * Logger options
 */
class LoggerOptions
{
    public ?int $level = null;
    public ?string $prefix = null;
    public ?bool $enableConsole = null;

    public function __construct(array $options = [])
    {
        $this->level = $options['level'] ?? LogLevel::INFO;
        $this->prefix = $options['prefix'] ?? '[BerrySdk]';
        $this->enableConsole = $options['enableConsole'] ?? true;
    }
}

/**
 * Logger class for BerrySdk
 */
class Logger
{
    private int $level;
    private string $prefix;
    private bool $enableConsole;

    public function __construct(?LoggerOptions $options = null)
    {
        $options = $options ?? new LoggerOptions();
        $this->level = $options->level ?? LogLevel::INFO;
        $this->prefix = $options->prefix ?? '[BerrySdk]';
        $this->enableConsole = $options->enableConsole ?? true;
    }

    /**
     * Format message with timestamp and level
     * 
     * @param string $level Level name
     * @param string $message Message
     * @param array $args Additional arguments
     * @return string Formatted message
     */
    private function formatMessage(string $level, string $message, array $args = []): string
    {
        $timestamp = date('c');
        $formattedArgs = '';
        
        if (!empty($args)) {
            $formattedArgs = ' ' . implode(' ', array_map(function($arg) {
                return is_array($arg) || is_object($arg) ? json_encode($arg, JSON_PRETTY_PRINT) : (string)$arg;
            }, $args));
        }

        return "{$this->prefix} [{$timestamp}] [{$level}] {$message}{$formattedArgs}";
    }

    /**
     * Log message at specified level
     * 
     * @param int $level Log level
     * @param string $levelName Level name
     * @param string $message Message
     * @param array $args Additional arguments
     */
    private function log(int $level, string $levelName, string $message, array $args = []): void
    {
        if ($this->level >= $level && $this->enableConsole) {
            $formattedMessage = $this->formatMessage($levelName, $message, $args);

            switch ($level) {
                case LogLevel::ERROR:
                    error_log($formattedMessage);
                    break;
                case LogLevel::WARN:
                    error_log($formattedMessage);
                    break;
                case LogLevel::INFO:
                    error_log($formattedMessage);
                    break;
                case LogLevel::DEBUG:
                    error_log($formattedMessage);
                    break;
            }
        }
    }

    /**
     * Log error message
     * 
     * @param string $message Message
     * @param array $args Additional arguments
     */
    public function error(string $message, array $args = []): void
    {
        $this->log(LogLevel::ERROR, 'ERROR', $message, $args);
    }

    /**
     * Log warning message
     * 
     * @param string $message Message
     * @param array $args Additional arguments
     */
    public function warn(string $message, array $args = []): void
    {
        $this->log(LogLevel::WARN, 'WARN', $message, $args);
    }

    /**
     * Log info message
     * 
     * @param string $message Message
     * @param array $args Additional arguments
     */
    public function info(string $message, array $args = []): void
    {
        $this->log(LogLevel::INFO, 'INFO', $message, $args);
    }

    /**
     * Log debug message
     * 
     * @param string $message Message
     * @param array $args Additional arguments
     */
    public function debug(string $message, array $args = []): void
    {
        $this->log(LogLevel::DEBUG, 'DEBUG', $message, $args);
    }

    /**
     * Set log level
     * 
     * @param int $level Log level
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * Set log prefix
     * 
     * @param string $prefix Prefix
     */
    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    /**
     * Set console enabled
     * 
     * @param bool $enabled Enabled flag
     */
    public function setConsoleEnabled(bool $enabled): void
    {
        $this->enableConsole = $enabled;
    }
}

// Default logger instance
$logger = new Logger();

// Environment-based logger configuration
if (getenv('NODE_ENV') === 'production') {
    $logger->setLevel(LogLevel::WARN);
} elseif (getenv('NODE_ENV') === 'test') {
    $logger->setConsoleEnabled(false);
} 