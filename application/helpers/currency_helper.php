<?php

/** GARRISON MODIFIED 4/20/2013 * */
function to_currency($number, $use_comma = false, $dec = 2)
{
    $CI = & get_instance();
    if (!$use_comma)
    {
        $currency_symbol = $CI->config->item('currency_symbol') ? $CI->config->item('currency_symbol') : '₦';
        if ($number >= 0)
        {
            if ($CI->config->item('currency_side') !== 'currency_side')
                return $currency_symbol . number_format($number, $dec, '.', '');
            else
                return number_format($number, $dec, '.', '') . $currency_symbol;
        }
        else
        {
            if ($CI->config->item('currency_side') !== 'currency_side')
                return '-' . $currency_symbol . number_format(abs($number), $dec, '.', '');
            else
                return '-' . number_format(abs($number), $dec, '.', '') . $currency_symbol;
        }
    }
    else
    {
        $currency_symbol = $CI->config->item('currency_symbol') ? $CI->config->item('currency_symbol') : '₦';
        if ($number >= 0)
        {
            if ($CI->config->item('currency_side') !== 'currency_side')
                return $currency_symbol . number_format($number, $dec);
            else
                return number_format($number, $dec) . $currency_symbol;
        }
        else
        {
            if ($CI->config->item('currency_side') !== 'currency_side')
                return '-' . $currency_symbol . number_format(abs($number), $dec);
            else
                return '-' . number_format(abs($number), $dec) . $currency_symbol;
        }
    }
}

/** END MODIFIED * */
function to_currency_no_money($number)
{
    return number_format($number, 2, '.', '');
}

?>