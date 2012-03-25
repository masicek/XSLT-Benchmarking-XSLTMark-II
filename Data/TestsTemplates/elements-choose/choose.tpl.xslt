<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

	<!-- output = default XML -->
	<xsl:output encoding="UTF-8" />

	<xsl:template match="/values">
		<presentedValues>
			{for $numberIdx=1 to $number}
				<xsl:choose>
					{if isset($lengthDummy)}
						{for $lengthDummyIdx=1 to $lengthDummy}
							<xsl:when test="value = 'Non exist element {$numberIdx} - {$lengthDummyIdx}'">
								<presented>{$numberIdx},{$lengthDummyIdx}</presented>
							</xsl:when>
						{/for}
					{/if}
					{for $lengthIdx=1 to $length}
						<xsl:when test="value = 'Element {$numberIdx} - {$lengthIdx}'">
							<presented>{$numberIdx},{$lengthIdx}</presented>
						</xsl:when>
					{/for}
					{if isset($otherwise) && $otherwise}
						<xsl:otherwise>
							<presented>Any presened by "value"</presented>
						</xsl:otherwise>
					{/if}
				</xsl:choose>
			{/for}
			{if isset($numberNot) && isset($lengthNot)}
				{for $numberIdx=1 to $numberNot}
					<xsl:choose>
						{for $lengthIdx=1 to $lengthNot}
							<xsl:when test="valueNot = 'Element {$numberIdx} - {$lengthIdx}'">
								<presented>{$numberIdx},{$lengthIdx}</presented>
							</xsl:when>
						{/for}
						{if isset($otherwiseNot) && $otherwiseNot}
							<xsl:otherwise>
								<presented>Any presened by "valueNot"</presented>
							</xsl:otherwise>
						{/if}
					</xsl:choose>
				{/for}
			{/if}
		</presentedValues>
	</xsl:template>

</xsl:stylesheet>
