<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

	<xsl:output encoding="UTF-8" />

	<xsl:include href="include1.xslt"/>
	<xsl:include href="include2.xslt"/>

	<xsl:template match="/">
		<rootGenerated>
			<xsl:call-template name="first" />
			<xsl:call-template name="second" />
		</rootGenerated>
	</xsl:template>

</xsl:stylesheet>
