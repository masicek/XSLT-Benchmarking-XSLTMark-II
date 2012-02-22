<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

	<xsl:output encoding="UTF-8" />

	<xsl:template match="node( ) | @*">
		<xsl:copy>
			<xsl:apply-templates select="@* | node( )"/>
		</xsl:copy>
	</xsl:template>

	<xsl:template match="testName">
		<xsl:element name="testName">
			<xsl:attribute name="value">
				<xsl:value-of select="text()"/>
			</xsl:attribute>
		</xsl:element>
	</xsl:template>

</xsl:stylesheet>
