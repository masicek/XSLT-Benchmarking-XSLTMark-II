{*
	Generate random string.
	If string form $exclude is generated, then next string are generated.
	Namy comment are added for eliminate echo whitespaces.

*}{assign var=charsetWithoutNum value="abcderfghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"}{*

*}{if ($whiteSpace == 1)}{*
	*}{assign var=charset value="         abcderfghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"}{*
*}{else}{*
	*}{assign var=charset value="abcderfghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"}{*
*}{/if}{*

*}{assign var=countWithoutNum value=strlen($charsetWithoutNum)}{*
*}{assign var=count value=strlen($charset)}{*
*}{assign var=length value=mt_rand(1, $maxLength)}{*

*}{*var_dump($exclude)*}{*

*}{if !isset($exclude)}{*
	*}{assign var=exclude value=[]}{*
*}{/if}{*

*}{while !isset($smarty.capture.string) || $smarty.capture.string == '' || in_array($smarty.capture.string, $exclude)}{*
	*}{capture name="string"}{/capture}{*
	*}{capture name="string"}{*
		First character is not number (and whitespace)
		*}{$charsetWithoutNum[mt_rand(0, $countWithoutNum-1)]}{*
		Next character allow numbers (and whitespace)
		*}{if $length > 1}{*
			*}{foreach array_fill(0, $length-1, 'dummy') as $itemvar}{*
				*}{$charset[mt_rand(0, $count-1)]}{*
			*}{/foreach}{*
		*}{/if}{*
	*}{/capture}{*
*}{/while}{*

Print generated string
*}{$smarty.capture.string}{*


*}{capture name="string"}{/capture}{*

*}