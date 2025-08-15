<?php

namespace Bibo\Mvc\Core\Enums;

use InvalidArgumentException;

/**
 * Enum representing different risk levels.
 *
 * This enum defines various risk levels that can be used in applications
 * to categorize or prioritize tasks, issues, or security concerns.
 */
enum RiskLevel: string
{
    /*
     * Low risk level.
     * This level indicates that the risk is minimal
     * and does not require immediate attention.
     * It can be used for tasks or issues that are not critical
     * and can be addressed at a later time.
     */
    case LOW = 'Low';

    /*
     * Medium risk level.
     * This level indicates that the risk is moderate
     * and should be monitored.
     * It can be used for tasks or issues that require attention
     * but are not urgent.
     */
    case MEDIUM = 'Medium';

    /*
     * High risk level.
     * This level indicates that the risk is significant
     * and requires immediate attention.
     * It can be used for tasks or issues that pose a
     * serious threat or concern
     * and need to be addressed promptly.
     */
    case HIGH = 'High';

    /*
     * Critical risk level.
     * This level indicates that the risk is severe
     * and poses an immediate threat.
     * It can be used for tasks or issues that are
     * extremely urgent
     * and need to be resolved immediately
     * to prevent significant harm or loss.
     */
    case CRITICAL = 'Critical';

    /*
     * No risk level.
     * This can be used to indicate that there is no associated risk
     * or that the risk is negligible.
     * This is useful in scenarios where risk assessment
     */
    case NONE = 'None';

    /**
     * Create a RiskLevel instance from a string value.
     *
     * @param string $value The string representation of the risk level.
     *                      It should be one of the defined risk levels
     *                      ('Low', 'Medium', 'High', 'Critical', 'None').
     *
     * @return self The corresponding RiskLevel instance.
     *
     * @throws InvalidArgumentException If the value does not match any risk level.
     */
    public static function fromValue(string $value): self
    {
        return match (strtolower($value)) {
            'low' => self::LOW,
            'medium' => self::MEDIUM,
            'high' => self::HIGH,
            'critical' => self::CRITICAL,
            'none' => self::NONE,
            default => throw new InvalidArgumentException('Invalid RiskLevel value: ' . $value),
        };
    }
}
