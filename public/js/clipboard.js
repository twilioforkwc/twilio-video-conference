function copyTextToClipboard(textStr){
    var copyItem = document.createElement("textarea");
    copyItem.textContent = textStr;
    var bodyElement = document.getElementsByTagName("body")[0];
    bodyElement.appendChild(copyItem);
    copyItem.select();
    var retVal = document.execCommand('copy');
    bodyElement.removeChild(copyItem);
    return retVal;
};
