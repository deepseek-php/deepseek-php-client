<?php

namespace DeepSeek\Enums\Configs;

enum TemperatureValues: string
{
    /** Recommended by DeepSeek docs: 0.0 for Coding / Math. */
    case CODING = '0.0';

    /**
     * @deprecated Use CODING instead. DeepSeek docs recommend the same temperature (0.0)
     *             for both Coding and Math. Kept for backward compatibility.
     * @see https://api-docs.deepseek.com/quick_start/parameter_settings
     */
    case MATH = '0.1';

    /** Recommended by DeepSeek docs: 1.0 for Data Cleaning / Data Analysis. */
    case DATA_ANALYSIS = '1.0';

    /**
     * @deprecated Use DATA_ANALYSIS instead. DeepSeek docs recommend the same temperature (1.0)
     *             for both Data Analysis and Data Cleaning. Kept for backward compatibility.
     * @see https://api-docs.deepseek.com/quick_start/parameter_settings
     */
    case DATA_CLEANING = '1.1';

    /** Recommended by DeepSeek docs: 1.3 for General Conversation / Translation. */
    case GENERAL_CONVERSATION = '1.3';

    /**
     * @deprecated Use GENERAL_CONVERSATION instead. DeepSeek docs recommend the same temperature (1.3)
     *             for both General Conversation and Translation. Kept for backward compatibility.
     * @see https://api-docs.deepseek.com/quick_start/parameter_settings
     */
    case TRANSLATION = '1.4';

    /** Recommended by DeepSeek docs: 1.5 for Creative Writing / Poetry. */
    case CREATIVE_WRITING = '1.5';

    /**
     * @deprecated Use CREATIVE_WRITING instead. DeepSeek docs recommend the same temperature (1.5)
     *             for both Creative Writing and Poetry. Kept for backward compatibility.
     * @see https://api-docs.deepseek.com/quick_start/parameter_settings
     */
    case POETRY = '1.6';
    case MAX_TOKENS = '4096';
    case RESPONSE_FORMAT_TYPE = 'text';
}
