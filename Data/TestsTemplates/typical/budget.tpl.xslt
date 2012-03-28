<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

	<!-- output = default XML -->
	<xsl:output encoding="UTF-8" />


	<xsl:variable name="mainCurrencySign" select="'Kc'" />


	<xsl:template match="/">
		<budgets parentCompanyName="Big Company Co.">
			<xsl:for-each select="is/company">
				<xsl:element name="budget">
					<xsl:attribute name="companyName">
						<xsl:value-of select="name" />
					</xsl:attribute>
					<xsl:copy-of select="responsiblePerson" />
					<xsl:apply-templates />
				</xsl:element>
			</xsl:for-each>
		</budgets>
	</xsl:template>


	<xsl:template match="contractor|subscriber">
		<value>
			<xsl:if test="name() = 'contractor'">
				<xsl:text>-</xsl:text>
			</xsl:if>
			<xsl:if test="name() = 'subscriber'">
				<xsl:text>+</xsl:text>
			</xsl:if>
			<xsl:call-template name="getPriceInCZK">
				<xsl:with-param name="price" select="price/text()" />
				<xsl:with-param name="currency" select="price/@currency" />
			</xsl:call-template>
		</value>
	</xsl:template>


	<xsl:template name="getPriceInCZK">
		<xsl:param name="price" />
		<xsl:param name="currency" />

		<xsl:choose>
			<xsl:when test="$currency = 'USD'">
				<xsl:value-of select="format-number($price * 18.40, '#')" />
			</xsl:when>
			<xsl:when test="$currency = 'EURO'">
				<xsl:value-of select="format-number($price * 24.40, '#')" />
			</xsl:when>
			<xsl:when test="$currency = 'GPB'">
				<xsl:value-of select="format-number($price * 29.00, '#')" />
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="format-number($price, '#')" />
			</xsl:otherwise>
		</xsl:choose>
		<xsl:text> </xsl:text>
		<xsl:value-of select="$mainCurrencySign" />
	</xsl:template>

	<xsl:template match="text()" />

</xsl:stylesheet>
