<?php

namespace DeepSeek\Enums;

enum Models: string
{
    /**
     * @deprecated since 2.1.0, will be removed in 3.0.0.
     * The 'deepseek-chat' alias retires from the DeepSeek API on 2026-07-24
     * (currently routes to deepseek-v4-flash non-thinking mode).
     * Use Models::V4_FLASH or Models::V4_PRO instead.
     */
    case CHAT = 'deepseek-chat';

    /**
     * @deprecated since 2.1.0, will be removed in 3.0.0.
     * The 'deepseek-coder' model no longer exists in the DeepSeek API.
     * Use Models::V4_PRO or Models::V4_FLASH instead.
     */
    case CODER = 'deepseek-coder';

    /**
     * @deprecated since 2.1.0, will be removed in 3.0.0.
     * The 'DeepSeek-R1' alias retires from the DeepSeek API on 2026-07-24
     * (currently routes to deepseek-v4-flash thinking mode).
     * Use Models::V4_FLASH with setThinking() (available v2.2.0+) instead.
     */
    case R1 = 'DeepSeek-R1';

    /**
     * @deprecated since 2.1.0, will be removed in 3.0.0.
     * 'DeepSeek-R1-Zero' was never a valid DeepSeek API model id.
     */
    case R1Zero = 'DeepSeek-R1-Zero';

    /**
     * DeepSeek-V4-Pro: flagship model. 1M context, max 384K output tokens.
     * Supports both thinking and non-thinking modes.
     *
     * @see https://api-docs.deepseek.com/quick_start/pricing
     */
    case V4_PRO = 'deepseek-v4-pro';

    /**
     * DeepSeek-V4-Flash: fast, efficient, economical. 1M context, max 384K output tokens.
     * Supports both thinking and non-thinking modes.
     *
     * @see https://api-docs.deepseek.com/quick_start/pricing
     */
    case V4_FLASH = 'deepseek-v4-flash';
}
