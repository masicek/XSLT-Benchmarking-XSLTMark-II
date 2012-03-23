<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

	<!-- output = default XML -->
	<xsl:output encoding="UTF-8" />

	<xsl:template match="/values">
		<presentedValues>
			{for $numberIdx=1 to $number}
				<xsl:if test="value = 'Element {$numberIdx}'">
					<presented>{$numberIdx}</presented>
				</xsl:if>
			{/for}
			{if isset($numberNot)}
				{for $numberIdx=1 to $numberNot}
					<xsl:if test="valueNot = 'Element {$numberIdx}'">
						<presented>{$numberIdx}</presented>
					</xsl:if>
				{/for}
			{/if}
		</presentedValues>
	</xsl:template>

</xsl:stylesheet>
