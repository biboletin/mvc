<?php

namespace Bibo\Mvc\Core\Enums;

use InvalidArgumentException;

/**
 * Enum representing different priority levels.
 *
 * This enum defines various priority levels that can be used in applications
 * to categorize or prioritize tasks, issues, or security concerns.
 */
enum Priority: string
{
    /*
     * Low priority level.
     * This level indicates that the task or issue is not urgent
     * and can be addressed later.
     */
    case LOW = 'Low';

    /*
     * Medium priority level.
     * This level indicates that the task or issue should be addressed
     * but is not critical.
     */
    case MEDIUM = 'Medium';

    /*
     * High priority level.
     * This level indicates that the task or issue is important
     * and should be addressed as soon as possible.
     */
    case HIGH = 'High';

    /*
     * Critical priority level.
     * This level indicates that the task or issue is of utmost importance
     * and requires immediate attention.
     */
    case CRITICAL = 'Critical';

    /*
     * No priority level.
     * This can be used to indicate that there is no associated priority
     * or that the priority is negligible.
     * This is useful in scenarios where priority assessment is not applicable.
     */
    case NONE = 'None';

    /*
     * Urgent priority level.
     * This level indicates that the task or issue is extremely urgent
     * and needs to be addressed immediately to prevent significant harm or loss.
     */
    case URGENT = 'Urgent';

    /*
     * Immediate priority level.
     * This level indicates that the task or issue requires immediate action
     * and cannot be delayed.
     */
    case IMMEDIATE = 'Immediate';

    /*
     * Emergency priority level.
     * This level indicates that the task or issue is an emergency
     * and requires immediate and decisive action.
     */
    case EMERGENCY = 'Emergency';

    /*
     * Blocker priority level.
     * This level indicates that the task or issue is blocking progress
     * and must be resolved before any further work can continue.
     */
    case BLOCKER = 'Blocker';

    /*
     * Trivial priority level.
     * This level indicates that the task or issue is of minimal importance
     * and can be addressed at a later time without significant impact.
     */
    case TRIVIAL = 'Trivial';

    /*
     * Optional priority level.
     * This level indicates that the task or issue is not essential
     * and can be addressed if time permits.
     */
    case OPTIONAL = 'Optional';

    /*
     * Default priority level.
     * This level can be used as a fallback or default value
     * when no specific priority is assigned.
     */
    case DEFAULT = 'Default';

    /*
     * Minor priority level.
     * This level indicates that the task or issue is of low importance
     * and can be addressed at a later time without significant impact.
     */
    case MINOR = 'Minor';

    /*
     * Major priority level.
     * This level indicates that the task or issue is of significant importance
     * and should be addressed promptly, but is not as critical as high or urgent.
     */
    case MAJOR = 'Major';

    /*
     * Severe priority level.
     * This level indicates that the task or issue is very serious
     * and requires immediate attention, but is not as critical as emergency or blocker.
     */
    case SEVERE = 'Severe';

    /*
     * Critical High priority level.
     * This level indicates that the task or issue is of critical importance
     * and requires immediate attention, but is categorized as high.
     */
    case CRITICAL_HIGH = 'Critical High';

    /*
     * Critical Low priority level.
     * This level indicates that the task or issue is of critical importance
     * but is categorized as low, meaning it requires attention but not immediately.
     */
    case CRITICAL_LOW = 'Critical Low';

    /*
     * Critical Medium priority level.
     * This level indicates that the task or issue is of critical importance
     * and requires attention, but is categorized as medium.
     */
    case CRITICAL_MEDIUM = 'Critical Medium';

    /*
     * Critical Urgent priority level.
     * This level indicates that the task or issue is of critical importance
     * and requires immediate attention, categorized as urgent.
     */
    case CRITICAL_URGENT = 'Critical Urgent';

    /*
     * Critical Immediate priority level.
     * This level indicates that the task or issue is of critical importance
     * and requires immediate action, categorized as immediate.
     */
    case CRITICAL_IMMEDIATE = 'Critical Immediate';

    /**
     * Create a Priority instance from a string value.
     *
     * @param string $value The string representation of the priority level.
     *                      It should be one of the defined priority levels
     *                      ('Low', 'Medium', 'High', 'Critical', 'None', etc.).
     *
     * @return self The corresponding Priority instance.
     *
     * @throws InvalidArgumentException If the value does not match any priority level.
     */
    public static function fromValue(string $value): self
    {
        return match (strtolower($value)) {
            'low' => self::LOW,
            'medium' => self::MEDIUM,
            'high' => self::HIGH,
            'critical' => self::CRITICAL,
            'none' => self::NONE,
            'urgent' => self::URGENT,
            'immediate' => self::IMMEDIATE,
            'emergency' => self::EMERGENCY,
            'blocker' => self::BLOCKER,
            'trivial' => self::TRIVIAL,
            'optional' => self::OPTIONAL,
            'default' => self::DEFAULT,
            'minor' => self::MINOR,
            'major' => self::MAJOR,
            'severe' => self::SEVERE,
            'critical high' => self::CRITICAL_HIGH,
            'critical low' => self::CRITICAL_LOW,
            'critical medium' => self::CRITICAL_MEDIUM,
            'critical urgent' => self::CRITICAL_URGENT,
            'critical immediate' => self::CRITICAL_IMMEDIATE,
            default => throw new InvalidArgumentException('Invalid Priority value: ' . $value),
        };
    }
}
