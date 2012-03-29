<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

	<xsl:output method="xml" indent="yes" encoding="UTF-8" />

	<xsl:template match="news">
		<rss version="2.0">
			<channel>
				<title><xsl:value-of select="@section" /></title>
				<link><xsl:value-of select="@link" /></link>
				<description><xsl:value-of select="about" /></description>
				<language>en</language>
				<pubDate><xsl:value-of select="@date" /></pubDate>
				<generator>Best News</generator>

				{if $itemPrintMethod == 'apply-templates'}
					<xsl:apply-templates select="reports" />
				{/if}
				{if $itemPrintMethod == 'for-each'}
					<xsl:call-template name="items"/>
				{/if}
			</channel>
		</rss>
	</xsl:template>

	{if $itemPrintMethod == 'apply-templates'}
		<xsl:template match="report">
			<item>
				<title><xsl:value-of select="@title" /></title>
				<link><xsl:value-of select="parent::reports/@link" />/?id=<xsl:value-of select="@id" /></link>
				<description><xsl:value-of select="abstract" /></description>
				<pubDate><xsl:value-of select="@date" /></pubDate>
				<guid><xsl:value-of select="@id" /></guid>
			</item>
		</xsl:template>
	{/if}

	{if $itemPrintMethod == 'for-each'}
		<xsl:template name="items">
			<xsl:for-each select="//reports/report">
				<item>
					<title><xsl:value-of select="@title" /></title>
					<link><xsl:value-of select="parent::reports/@link" />/?id=<xsl:value-of select="@id" /></link>
					<description><xsl:value-of select="abstract" /></description>
					<pubDate><xsl:value-of select="@date" /></pubDate>
					<guid><xsl:value-of select="@id" /></guid>
				</item>
			</xsl:for-each>
		</xsl:template>
	{/if}

</xsl:stylesheet>
