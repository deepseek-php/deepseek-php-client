<?php

namespace DeepSeek\Enums\Configs;

enum TemperatureValues: string
{
    case CODING = "0.0";
    case MATH = "0.1";
    case DATA_ANALYSIS = "1.0";
    case DATA_CLEANING = "1.1";
    case GENERAL_CONVERSATION = "1.3";
    case TRANSLATION = "1.4";
    case CREATIVE_WRITING = "1.5";
    case POETRY = "1.6";
    case MAX_TOKENS = "4096";
    case RESPONSE_FORMAT_TYPE = "text";
}
