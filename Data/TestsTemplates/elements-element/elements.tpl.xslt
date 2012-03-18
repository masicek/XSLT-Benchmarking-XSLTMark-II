<?xml version="1.0" encoding="UTF-8"?>

{if $type == 'xslt'}
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:output encoding="UTF-8" />

	<xsl:template match="/">
		<xsl:element name="root">
{else}
<root>
{/if}

{mt_srand($seed)}

{capture name="includedGenerator"}{$templateDir}generator.tpl.xslt{/capture}
{assign var=includedGenerator value=$smarty.capture.includedGenerator}
{capture name="includedRandomString"}{$templateDir}randomString.tpl{/capture}
{assign var=includedRandomString value=$smarty.capture.includedRandomString}

{include file=$includedGenerator depth=0 parent=time()}

{if $type == 'xslt'}
		</xsl:element>
	</xsl:template>
</xsl:stylesheet>
{else}
</root>
{/if}