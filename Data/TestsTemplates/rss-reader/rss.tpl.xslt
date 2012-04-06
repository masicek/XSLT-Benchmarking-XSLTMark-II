<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output method="html" encoding="UTF-8" indent="no" />

	<xsl:template match="rss/channel">
		<html>
			<head>
				<title>RSS for channel <xsl:value-of select="title"/></title>
			</head>
			<body>
				<h1>
					<a href="{link}">
						<xsl:value-of select="title"/>
					</a>
				</h1>
				<p>
					<xsl:value-of select="description"/>
				</p>
				<xsl:apply-templates select="item" />
			</body>
		</html>
	</xsl:template>

	<xsl:template match="item">
		<h2>
			<a>
				<xsl:attribute name="href">
					<xsl:value-of select="link"/>
				</xsl:attribute>
				<xsl:value-of select="title"/>
			</a>
		</h2>
		<p>
			<xsl:value-of select="description" />
		</p>
	</xsl:template>

</xsl:stylesheet>