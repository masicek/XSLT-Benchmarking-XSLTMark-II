<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xslo="http://www.w3.org/1999/XSL/TransformAlias"
	version="1.0"
>

	<xsl:output method="xml" encoding="UTF-8" />

	<xsl:namespace-alias stylesheet-prefix="xslo" result-prefix="xsl"/>

	<xsl:template match="/">
		<xslo:stylesheet version="1.0">
			<xsl:for-each select="//rule">
				<xslo:template match="{@from}">
					<xslo:element name="{@to}">
						<xslo:apply-templates/>
					</xslo:element>
				</xslo:template>
			</xsl:for-each>
		</xslo:stylesheet>
	</xsl:template>

</xsl:stylesheet>
