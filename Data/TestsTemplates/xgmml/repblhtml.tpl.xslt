<?xml version="1.0"?>
<xsl:stylesheet
     version="1.0"
     xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" encoding="UTF-8" indent="no" />

<xsl:template match="text()"></xsl:template>

<!--
	===========	From XGMML  TO HTML Broken Links Report  =================
			     by John Punin
-->

<xsl:template match="/">
<html>
<head>
<title>Broken Links</title>
</head>

<body bgcolor="#ffffff">
<h1>List of Broken Links</h1>
<b>Last Update :</b>
<br></br>
<hr></hr>
This Report gives a List of unique Broken Links (http
code 404). Each broken link is reported with the HTML page and the caption
where the broken link is found.
<hr></hr>
<ol>
<xsl:apply-templates/>
</ol>
</body>
</html><xsl:text>
</xsl:text>
</xsl:template>

<!--
	=============For all ELEMENTS in XGMML===============
-->


<xsl:template match="/graph/node/att"><xsl:apply-templates select="@name"/></xsl:template>


<!--name-->
<xsl:template match="@name[.='code' and ../@value='404']">
<xsl:variable name="tid"><xsl:value-of select="../../@id"/>
</xsl:variable>
<xsl:variable name="tlab"><xsl:value-of select="../../@label"/>
</xsl:variable>

<xsl:for-each select="/graph/edge/@target[.=$tid]">
<xsl:variable name="sid"><xsl:value-of select="../@source"/>
</xsl:variable>
<xsl:variable name="cap"><xsl:value-of select="../@label"/>
</xsl:variable>
  <xsl:for-each select="/graph/node/@id[.=$sid]">
    <xsl:variable name="slab"><xsl:value-of select="../@label"/>
    </xsl:variable>
    <xsl:for-each select="../att/@name[.='title']">
      <xsl:variable name="tit"><xsl:value-of select="../@value"/>
      </xsl:variable>
<li>
<pre><xsl:value-of select="$tlab"/></pre>
<pre><b>Caption:</b><xsl:value-of select="$cap"/></pre>
<a href="{$slab}"><xsl:value-of select="$tit"/><br></br>
<xsl:value-of select="$slab"/></a>
</li>
    </xsl:for-each>
  </xsl:for-each>
</xsl:for-each>
</xsl:template>

<xsl:template match="@name[not(.='code' and ../@value='404')]">
</xsl:template>

</xsl:stylesheet>

