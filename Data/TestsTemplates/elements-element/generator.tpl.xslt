{*
	Generate random number of elements with random attributes.
	Names of elements and attributes are random string without whitespace.
	Values of attributes are random string with whitespaces.
	If actual depth is greater then maximum depth, return noathing.
*}

{if $depth <= $maxDepth}

	{assign var=random value=mt_rand(0, $maxChildren)}
	{if $random > 0}
		{foreach array_fill(0, $random, 'dummy') as $itemvar}
			{assign var=randomAtt value=mt_rand(0, $maxAttributes)}

			{if $type == "xslt"}
				{capture name="elementName"}{include file=$includedRandomString whiteSpace=FALSE maxLength=$maxNameLength}{/capture}
				<xsl:element name="{$smarty.capture.elementName}">
					{assign var=usedAttributes value=[]}
					{section name=idx2 loop=$randomAtt}
						{capture name="attributeName"}{include file=$includedRandomString whiteSpace=FALSE maxLength=$maxNameLength exclude=$usedAttributes}{/capture}
						{capture name="devNull"}{array_push($usedAttributes, $smarty.capture.attributeName)}{/capture}
						<xsl:attribute name="{$smarty.capture.attributeName}">{include file=$includedRandomString whiteSpace=TRUE maxLength=$maxAttributeValueLength}</xsl:attribute>
					{/section}
					{include file=$includedGenerator depth=$depth+1 parent=$smarty.capture.elementName}
				</xsl:element>
			{/if}

			{if $type == "xml"}
				{capture name="elementName"}{include file=$includedRandomString whiteSpace=FALSE maxLength=$maxNameLength}{/capture}
				<{$smarty.capture.elementName}
					{assign var=usedAttributes value=[]}
					{section name=idx2 loop=$randomAtt}
						{capture name="attributeName"}{include file=$includedRandomString whiteSpace=FALSE maxLength=$maxNameLength exclude=$usedAttributes}{/capture}
						{capture name="devNull"}{array_push($usedAttributes, $smarty.capture.attributeName)}{/capture}
						{$smarty.capture.attributeName}="{include file=$includedRandomString whiteSpace=TRUE maxLength=$maxAttributeValueLength}"
					{/section}
				>
					{include file=$includedGenerator depth=$depth+1 parent=$smarty.capture.elementName}
				</{$smarty.capture.elementName}>
			{/if}

		{/foreach}
	{/if}
	{capture name="elementName"}{$parent}{/capture}
{/if}

