// Arguments: [VERSION]
var oArgs = WScript.Arguments;
version = oArgs(0);

// input XML
try
{
	var xml = new ActiveXObject("MSXML2.DOMDocument." + version);
	WScript.Echo('available');
}
catch (err)
{
	WScript.Echo('not-available');
}
