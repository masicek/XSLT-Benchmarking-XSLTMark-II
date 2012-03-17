<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

	<xsl:output encoding="UTF-8" />

	{if $type == 'nonprocedural'}
	<!-- need for ignore company decription -->
	<xsl:template match="text()" />

	<xsl:template match="/companies">
		<companies>
			<xsl:apply-templates />
		</companies>
	</xsl:template>

	<!-- temaplte with "apply-templates" with "select" -->
	<xsl:template match="company">
		<!-- ignore company desription -->
		<company>
			<address>
				<xsl:value-of select="@name" />
				<xsl:text>, </xsl:text>
				<xsl:apply-templates select="address" />
			</address>
			<xsl:apply-templates select="employee" />
		</company>
	</xsl:template>

	<!-- template without "apply-templates" -->
	<xsl:template match="address">
		<xsl:value-of select="street" />
		<xsl:text>{$delimiter}</xsl:text>
		<xsl:value-of select="number" />
		<xsl:text>, </xsl:text>
		<xsl:value-of select="city" />
		<xsl:text>, </xsl:text>
		<xsl:value-of select="postcode" />
	</xsl:template>

	<xsl:template match="employee">
		<employee>
			<xsl:value-of select="name" />
			<xsl:text>{$delimiter}</xsl:text>
			<xsl:value-of select="surname" />
			<xsl:text> - </xsl:text>
			<xsl:value-of select="pay" />
			<xsl:text>,-</xsl:text>
		</employee>
	</xsl:template>
	{/if}


	{if $type == 'procedural'}
	<!-- need for start of procedural trasformation -->
	<xsl:template match="/companies">
		<companies>
			<xsl:for-each select="company">
				<xsl:call-template name="company" />
			</xsl:for-each>
		</companies>
	</xsl:template>

	<!-- temaplte with "call-template" with "with-param" -->
	<xsl:template name="company">
		<!-- ignore company desription -->
		<company>
			<address>
				<xsl:value-of select="@name" />
				<xsl:text>, </xsl:text>
				<xsl:call-template name="address">
					<xsl:with-param name="address" select="address" />
				</xsl:call-template>
			</address>
			<xsl:for-each select="employee">
				<xsl:call-template name="employee">
					<xsl:with-param name="employee" select="." />
				</xsl:call-template>
			</xsl:for-each>
		</company>
	</xsl:template>

	<!-- without "call-template" -->
	<xsl:template name="address">
		<xsl:param name="address"/>

		<xsl:value-of select="$address/street" />
		<xsl:text>{$delimiter}</xsl:text>
		<xsl:value-of select="$address/number" />
		<xsl:text>, </xsl:text>
		<xsl:value-of select="$address/city" />
		<xsl:text>, </xsl:text>
		<xsl:value-of select="$address/postcode" />
	</xsl:template>

	<xsl:template name="employee">
		<xsl:param name="employee"/>
		<employee>
			<xsl:value-of select="$employee/name" />
			<xsl:text>{$delimiter}</xsl:text>
			<xsl:value-of select="$employee/surname" />
			<xsl:text> - </xsl:text>
			<xsl:value-of select="$employee/pay" />
			<xsl:text>,-</xsl:text>
		</employee>
	</xsl:template>
	{/if}

</xsl:stylesheet>
