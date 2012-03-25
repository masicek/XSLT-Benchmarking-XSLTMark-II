<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

	<!-- output = default XML -->
	<xsl:output encoding="UTF-8" />

	<xsl:template match="/elements">
		<modifiedElements>
			{for $numberIdx=1 to $number}
				<xsl:for-each select="subelement_{$numberIdx}">
					<modifiedSubelement_{$numberIdx}/>
				</xsl:for-each>
			{/for}
			{if isset($numberNot)}
				{for $numberIdx=1 to $numberNot}
					<xsl:for-each select="notPresenedSubelement_{$numberIdx}">
						<modifiedSubelement_{$numberIdx}/>
					</xsl:for-each>
				{/for}
			{/if}
		</modifiedElements>
	</xsl:template>

</xsl:stylesheet>
