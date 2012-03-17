<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

	<xsl:output encoding="UTF-8" method="xml"/>

	<xsl:template match="/">
		Test string
Not indented test string
		Escaped characters &lt; &amp;
		<xsl:text>Test string in element text</xsl:text>
		<xsl:text>Escaped characters in element text &lt; &amp;</xsl:text>
		<xsl:text disable-output-escaping="yes">Not escaped characters in element text &lt; &amp;</xsl:text>
		<xsl:text>
			Element text
			with text
			on more lines
		</xsl:text>

		<xsl:text>Many text elements - 1</xsl:text>
		<xsl:text> </xsl:text>
		<xsl:text>Many text elements - 2</xsl:text><xsl:text> </xsl:text>
		<xsl:text>Many text elements - 3</xsl:text>
		<xsl:text>Many text elements - 4</xsl:text>
		<xsl:text>Many text elements - 5</xsl:text>
		<xsl:text>Many text elements - 6</xsl:text>
		<xsl:text>Many text elements - 7</xsl:text>
		<xsl:text>Many text elements - 8</xsl:text>
		<xsl:text>Many text elements - 9</xsl:text>
		<xsl:text>Many text elements - 10</xsl:text>
	</xsl:template>

</xsl:stylesheet>
