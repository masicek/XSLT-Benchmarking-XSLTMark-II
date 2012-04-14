<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="2.0">

	<xsl:output encoding="UTF-8"
		{if $element == 'character-map'}
			use-character-maps="myMap"
		{/if}
	/>

	{if $element == 'character-map'}
		<xsl:character-map name="myMap">
			<xsl:output-character character="#" string="HASH" />
			<xsl:output-character character="@" string="AT" />
			<xsl:output-character character="%" string="PERCENT" />
		</xsl:character-map>
	{/if}

	<xsl:template match="/">
		<rootGenerated>
			{if $element == 'character-map'}
				<hash>#</hash>
				<at>@</at>
				<percent>%</percent>
			{/if}
		</rootGenerated>
	</xsl:template>

</xsl:stylesheet>
