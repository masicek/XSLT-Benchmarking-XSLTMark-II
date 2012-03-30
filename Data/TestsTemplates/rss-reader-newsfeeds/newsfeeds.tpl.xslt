<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="xml" omit-xml-declaration="yes" indent="no" encoding="UTF-8" />

	<xsl:template match="rss/channel">
		<div class="body">
			<ul class="ArticleEntry">
				<xsl:apply-templates select="item" />
			</ul>
		</div>
	</xsl:template>

	<xsl:template match="item">
		<xsl:if test="position() &lt; 5">
			<li><a href="{link}" target="_NEW"><xsl:value-of select="title" /></a></li>
			<div id="" style="display:none;">
				<xsl:value-of select="pubDate" />  - <a href="mailto:{author}">Email The Author</a>
				<div class="ArticleDescription">
					<xsl:value-of select="description" />
				</div>
			</div>
		</xsl:if>
	</xsl:template>

</xsl:stylesheet>