<?xml version="1.0" encoding="utf-8"?>
<myxsl:stylesheet version="1.0"
	xmlns:myxsl="http://www.w3.org/1999/XSL/Transform"

	xmlns:defaultXml="http://www.namespaces.net/defaultXml"
	xmlns:anotherName="http://www.namespaces.net/defaultXslt"
>

	<myxsl:output method="xml" encoding="UTF-8" />

	<myxsl:template match="/">
		<generatedRoot>
			<myxsl:apply-templates />
		</generatedRoot>
	</myxsl:template>

	<myxsl:template match="defaultXml:element1">
		<el1><myxsl:value-of select="text()" /></el1>
		<myxsl:apply-templates />
	</myxsl:template>

	<myxsl:template match="anotherName:element2">
		<el2><myxsl:value-of select="text()" /></el2>
		<myxsl:apply-templates />
	</myxsl:template>

	<myxsl:template match="text()">
	</myxsl:template>

</myxsl:stylesheet>
