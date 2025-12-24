<?php

namespace Bibo\Mvc\Core\Utilities;

use Bibo\Mvc\Core\Utilities\Utils\ArrayUtils;
use Bibo\Mvc\Core\Utilities\Utils\DateUtils;
use Bibo\Mvc\Core\Utilities\Utils\FileUtils;
use Bibo\Mvc\Core\Utilities\Utils\FloatUtils;
use Bibo\Mvc\Core\Utilities\Utils\FormatUtils;
use Bibo\Mvc\Core\Utilities\Utils\IntUtils;
use Bibo\Mvc\Core\Utilities\Utils\JsonUtils;
use Bibo\Mvc\Core\Utilities\Utils\MathUtils;
use Bibo\Mvc\Core\Utilities\Utils\RegexUtils;
use Bibo\Mvc\Core\Utilities\Utils\SecurityUtils;
use Bibo\Mvc\Core\Utilities\Utils\StringUtils;
use Bibo\Mvc\Core\Utilities\Utils\ValidationUtils;

/**
 * Utilities class
 */
class Utilities
{
    use StringUtils;
    use ArrayUtils;
    use IntUtils;
    use FloatUtils;
    use DateUtils;
    use ValidationUtils;
    use FileUtils;
    use MathUtils;
    use SecurityUtils;
    use RegexUtils;
    use FormatUtils;
    use JsonUtils;
}
