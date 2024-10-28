<?php

namespace Vertuoza\Formatter;

use Monolog\Formatter\JsonFormatter;
use Vertuoza\Enums\LevelSeverityEnum;

class GcpLoggerFormatter extends JsonFormatter
{
    /**
     * @var array
     */
    protected array $labelProperties = [];

    /**
     * @param array $labelProperties
     */
    function __construct(array $labelProperties = [])
    {
        parent::__construct();
        $this->labelProperties = $labelProperties;
    }

    /**
     * @param $data
     * @param bool $ignoreErrors
     *
     * @return string
     */
    public function toJson($data, bool $ignoreErrors = false): string
    {
        return parent::toJson($this->toGcp($data), $ignoreErrors);
    }

    /**
     * @param array $record
     *
     * @return array
     */
    protected function toGcp(array $record): array
    {
        $formatted = [
            'message' => $record['message'],
            'severity' => LevelSeverityEnum::tryFrom($record['level_name']) ?? LevelSeverityEnum::ALERT,
            'timestamp' => $record['datetime'],
            'channel' => $record['channel'],
        ];

        if ($this->ignoreEmptyContextAndExtra) {
            return $formatted;
        }

        if (is_array($record['context'])) {
            foreach ($this->labelProperties as $labelProperty) {
                if (!isset($record['context'][$labelProperty])){
                    continue;
                }

                $formatted['labels'][$labelProperty] = $record['context'][$labelProperty];
                unset($record['context'][$labelProperty]);
            }

            $formatted = array_merge($record['context'], $formatted);
        }

        if (is_array($record['extra'])) {
            $formatted = array_merge($record['extra'], $formatted);
        }

        return $formatted;
    }
}
