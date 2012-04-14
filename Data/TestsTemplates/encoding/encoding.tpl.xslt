<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

	<xsl:output {if $useEncoding == '1'}encoding="{$encoding}"{/if} method="xml"/>

	<xsl:template match="/">
		<root>
			<xsl:copy-of select="/root/element" />
		</root>
	</xsl:template>

</xsl:stylesheet>
