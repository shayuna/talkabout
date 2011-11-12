<?php
    session_start();

    $tolkBtnTxt="talk";
    $ditchBtnTxt="leave";

    $sbjDitchMsg="your partner wants to leave the conversation";
    $ditchBtnFreeTxt="let him go";
    $ditchBtnIgnoreTxt="pretend not to hear";
    $contBtnTxt="go with the flow";
    $aboutLinkTxt="about";
    $isTypingMsgArea="your partner is typing a message";
    $sbj=$_GET["sbj"];
//    $sbj=str_replace("\"","\\"",sbj);
    $fromTolkBox=$_GET["fromTolkBox"];
?>
<!DOCTYPE html>
<html>
<head>
<title>talkabout</title>
<link rel="stylesheet" href="style_tolkbox.css" />

</head>

<body onload="onLoad()" onunload="moveOn()">
<div id="tolkaboutBody">
    <div id="manageTolkArea">
        <div class="tolkArea" id="listTolkArea">
            <div class="tolkBackBox" >
                <div>
                     <textarea class="postInput" id="fromListSubjectPostInput" onfocus="chkTolkBoxOnEnter(this,0)" onblur="chkTolkBoxOnExit(this,0)" onkeydown="updateTypingStatus(this)"></textarea>
                 </div>
                 <div>
                     <input type="button" class="tolkBtn" value="<?=$tolkBtnTxt?>" onclick="sendMsg(2)"/>
                     <input type="button" class="ditchBtn" value="<?=$ditchBtnTxt?>" onclick="ditchDialogRequest(2,0)"/>
                 </div>
            </div>
            <div class="tolkBox" id="fromListSubjectTolkBox"></div>
            <div id="fromListSubjectIsTypingMsgArea"><span><?=$isTypingMsgArea?></span></div>
        </div>
        <div class="tolkArea" id="myTolkArea">
            <div class="tolkBackBox" >
                <div>
                     <textarea class="postInput" id="newSubjectPostInput" onfocus="chkTolkBoxOnEnter(this,1)" onblur="chkTolkBoxOnExit(this,1)" onkeydown="updateTypingStatus(this)"></textarea>
                 </div>
                 <div>
                     <input type="button" class="tolkBtn" value="<?=$tolkBtnTxt?>" onclick="sendMsg(1)"/>
                     <input type="button" class="ditchBtn" value="<?=$ditchBtnTxt?>" onclick="ditchDialogRequest(1,0)"/>
                 </div>
            </div>
            <div class="tolkBox" id="newSubjectTolkBox"></div>
            <div id="newSubjectIsTypingMsgArea"><span><?=$isTypingMsgArea?></span></div>
        </div>
    </div>
    <div id="isDitchDlgBox" class="msgToUser msgToUser_Medium">
        <div>
            <div>
                <span id="ditchMsgTxt"><?=$sbjDitchMsg?></span>
            </div>
            <div class="ditchBoxBtnsLine">
                <input type="button" id="freeWillieBtn" value="<?=$ditchBtnFreeTxt?>" onclick="ditchResult(1)"/>
                <input type="button" id="ignoreBtn" value="<?=$ditchBtnIgnoreTxt?>" onclick="ditchResult(0)"/>
            </div>
        </div>
    </div>
    <div id="usrMsg" class="msgToUser">
        <div>
            <div>
                <span id="usrMsgTxt"></span>
            </div>
            <div class="usrMsgBtnLine">
                <input id="usrMsgBtn" type="button" value="<?=$contBtnTxt?>" onclick="closeUsrMsg()"/>
            </div>
        </div>
    </div>
</div>
<div id="curtain"></div>
 </body>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>

<script language="javascript" type="text/javascript">
    var sbj="<?=sbj?>";
    var fromTolkBox="<?=fromTolkBox?>";
    var newSubjectIndxAr = [];
    var newSubjectIndx="";
    var subjectFromListIndx="";
    var newSubjectIsTypingStatus=0;
    var fromListSubjectIsTypingStatus=0;
    var toDitchRequestFrom=0;
    var swIsLockGetMsg=false;
    var getMsgInterval=4000;
    var hndlGetMsg;
    var focusId="";
    var lngSlctCode="df";
    var msgInNewTolkBox="";
    var msgInFromListTolkBox="";
    var sbjStartTolkMsg="";
    var msgSendAgainMsg="";
    var sbjConLostMsg="";
    var ditchPartnerRequestTtl="";
    var ditchPartnerFinalTtl="";
    var partnerTolksTtl="";
    var youTolkTtl="";
    var sbjInputPlaceHolder="";
    var sbjInputTtl="";
    var sbjAddBtnTxt="";
    var tolkBtnTxt="";
    var ditchBtnTxt="";
    var sbjDitchMsg="";
    var ditchBtnFreeTxt="";
    var ditchBtnIgnoreTxt="";
    var contBtnTxt="";
    var aboutLinkTxt="";
    var iDitchMsg="";
    var youDitchMsg="";
    var conversationShutDownMsg="";
    var isTypingMsgArea="";
    var siteTxtAr;

    function getWndHeight() {
      var myHeight = 0;
      if( typeof( window.innerHeight ) == 'number' ) {
        //Non-IE
        myHeight = window.innerHeight;
      } else if( document.documentElement && document.documentElement.clientHeight ) {
        //IE 6+ in 'standards compliant mode'
        myHeight = document.documentElement.clientHeight;
      } else if( document.body && document.body.clientHeight ) {
        //IE 4 compatible
        myHeight = document.body.clientHeight;
      }
      return myHeight;
    }
    function getDocHeight() {
        var D = document;
        return Math.max(
            Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
            Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
            Math.max(D.body.clientHeight, D.documentElement.clientHeight)
        );
    }
    function getDocWidth() {
        var D = document;
        return Math.max(
            Math.max(D.body.scrollWidth, D.documentElement.scrollWidth),
            Math.max(D.body.offsetWidth, D.documentElement.offsetWidth),
            Math.max(D.body.clientWidth, D.documentElement.clientWidth)
        );
    }
    $(document).ready(function(){
       $(".msgToUser").css("display","none");
       $("#curtain").removeClass("curtainDown").addClass("curtainUp");
       resetAllSiteTxt();
       $("#newSubjectPostInput").html(msgInNewTolkBox);
       $("#fromListSubjectPostInput").html(msgInFromListTolkBox);
        $("#newSubjectIsTypingMsgArea").css("visibility","hidden");
        $("#fromListSubjectIsTypingMsgArea").css("visibility","hidden");
        document.getElementById("curtain").style.height=getDocHeight()+"px";
    })
    function onLoad()
    {
       hndlGetMsg=window.setTimeout ("getMsg()",getMsgInterval);
       disableMyTolkArea(true);
       disableListTolkArea(true);
       $("#tolkaboutBody").css("visibility","visible");
       if (fromTolkBox=="2")$("#myTolkArea").css("display","none");
       else $("#listTolkArea").css("display","none");               
       var boxContent=sbjStartTolkMsg+" "+sbj;
       newSubjectIndx=window.opener.newSubjectIndx;
       subjectFromListIndx=window.opener.subjectFromListIndx;
       postMsg(boxContent,fromTolkBox);
    }
    function disableMyTolkArea(toDisable)
    {
           if (toDisable)
           {
               $("#myTolkArea textarea").attr("disabled","disabled");
               $("#myTolkArea input").attr("disabled","disabled");
           }
           else
           {
               $("#myTolkArea textarea").attr("disabled","");
               $("#myTolkArea input").attr("disabled","");
           }
    }
    function disableListTolkArea(toDisable)
    {
           if (toDisable)
           {
               $("#listTolkArea textarea").attr("disabled","disabled");
               $("#listTolkArea input").attr("disabled","disabled");
           }
           else
           {
               $("#listTolkArea textarea").attr("disabled","");
               $("#listTolkArea input").attr("disabled","");
           }
    }
    function isLockGetMsg()
    {
        return swIsLockGetMsg;
    }
    function setIsLockGetMsg(isLock)
    {
        swIsLockGetMsg=isLock;
    }
    function postMsg(msg,fromBoxNo)
    {
        var subjectIndx="";
        if (fromBoxNo==1)subjectIndx=newSubjectIndx;
        else subjectIndx=subjectFromListIndx;
        $.ajax({
          url: "postMsg.php?msg="+msg+"&subjectIndx="+subjectIndx+"&newSubjectIndx="+newSubjectIndx+
              "&subjectFromListIndx="+subjectFromListIndx+"&lng="+lngSlctCode,
          success: function(data) {
            if (parseInt(data,10)==0)
            {
                if (fromBoxNo==1) setTheeFocus("newSubjectPostInput");
                else setTheeFocus("fromListSubjectPostInput");
                openUsrMsg(msgSendAgainMsg);
            }
            else if (parseInt(data,10)>0)
            {
                if (fromBoxNo==1)
                {
                    $("#newSubjectPostInput").attr("value","");
                    chkTolkBoxOnExit(document.getElementById("newSubjectPostInput"),1);
                    newSubjectIsTypingStatus=0;
                }
                else
                {
                    $("#fromListSubjectPostInput").attr("value","");
                    chkTolkBoxOnExit(document.getElementById("fromListSubjectPostInput"),0);
                    fromListSubjectIsTypingStatus=0;
                }
                getMsg();
            }
            else
            {
                if (fromBoxNo==1) setTheeFocus("newSubjectPostInput");
                else setTheeFocus("fromListSubjectPostInput");
                openUsrMsg(sbjConLostMsg,3);
            }}
        });
    }
    function getMsg()
    {
        if (!isLockGetMsg())
        {
            setIsLockGetMsg(true);
            $.ajax({

              url: "getMsg.php?newSubjectIndx="+newSubjectIndx+"&subjectFromListIndx="+subjectFromListIndx+
                    "&myList="+serializeAr(newSubjectIndxAr),
              error: function(){
                  setIsLockGetMsg(false);
                  getMsgInterval=100;
                  window.clearTimeout(hndlGetMsg);
                  hndlGetMsg=window.setTimeout ("getMsg()",getMsgInterval);
              } ,
              success: function(data) {
                   // debug info. get rid of in real life
/*
                   if (data.indexOf("fishy")>-1)
                   {
                        alert (data);
                   }
*/
                   var msgAr=data.split("*&*");
                   var myMsg=msgAr[0];
                   var yourMsg="";
                   if (msgAr.length==2) yourMsg=msgAr[1];
                   if (myMsg!="")
                   {
                        var tmpAr1=myMsg.split("*^*");
                        if (tmpAr1.length>1)
                        {
                           if (tmpAr1[0]=="ditchRequest222")
                           {
                                toggleDitchBox(1);
                                toDitchRequestFrom=1;
                                setTitleText(ditchPartnerRequestTtl);
                           }
                           else if (tmpAr1[0]=="ditch111" || tmpAr1[0]=="ditch333")
                           {
                               newSubjectIndx="";
                               forceTolkBoxTxt(document.getElementById("newSubjectPostInput"),1);
                               disableMyTolkArea(true);
                               var tolkBoxTxt="";
            //                   var tolkBoxTxt=$("#newSubjectTolkBox").html()+msgAr[0];
                               $("#newSubjectTolkBox").html(tolkBoxTxt);

                               var txtToUse="";
                               if (tmpAr1[0]=="ditch111")
                               {
                                   setTitleText(ditchPartnerFinalTtl);
                                   txtToUse=iDitchMsg;
                                   if (tmpAr1[1]=="2")txtToUse=youDitchMsg;
                               }
                               else
                               {
                                   txtToUse=conversationShutDownMsg;
                                   newSubjectIndxAr = [];
                                   updateSubjectsList();
                               }
                               updateTypingAreaVisiblity(true,false);
                               openUsrMsg(txtToUse);
                            }
                            else if (tmpAr1[0]=="partnerTyping444")
                            {
                                updateTypingAreaVisiblity(true,true);
                            }
                            else if (!isNaN(tmpAr1[0]))
                            {
                               newSubjectIndx=tmpAr1[0];
                               disableMyTolkArea(false);
                               var rspContentAr=tmpAr1[1].split("*$*");
                               var isOwner=parseInt(rspContentAr[1],10);
                               var msgTxt=rspContentAr[0];
                               var lng=rspContentAr[2];
                               setTranslatedMsg(1,msgTxt,isOwner,lng);
                               if ($("#newSubjectTolkBox>div:last-child").hasClass("yourTolkItemElm"))
                                    setTitleText(partnerTolksTtl);
                               else setTitleText(youTolkTtl);
                               if (isOwner!=1)updateTypingAreaVisiblity(true,false);
                               if (msgTxt.indexOf(sbjStartTolkMsg)>-1)window.scrollTo(0,document.body.scrollHeight);
                            }
                        }
                   }
                   if (yourMsg!="")
                   {
                        var tmpAr1=yourMsg.split("*^*");
                        if (tmpAr1.length>1)
                        {
                           if (tmpAr1[0]=="ditchRequest222")
                           {
                                toDitchRequestFrom=2;
                                toggleDitchBox(1);
                                setTitleText(ditchPartnerRequestTtl);
                           }
                           else if (tmpAr1[0]=="ditch111" || tmpAr1[0]=="ditch333")
                           {
                               subjectFromListIndx="";
                               forceTolkBoxTxt(document.getElementById("fromListSubjectPostInput"),0);
                               disableListTolkArea(true);
                               var tolkBoxTxt="";
            //                   var tolkBoxTxt=$("#fromListSubjectTolkBox").html()+msgAr[1];
                               $("#fromListSubjectTolkBox").html(tolkBoxTxt);
                               var txtToUse="";
                               if (tmpAr1[0]=="ditch111")
                               {
                                   setTitleText(ditchPartnerFinalTtl);
                                   txtToUse=iDitchMsg;
                                   if (tmpAr1[1]=="2")txtToUse=youDitchMsg;
                               }
                               else
                               {
                                    txtToUse=conversationShutDownMsg;
                               }
                               updateTypingAreaVisiblity(false,false);
                               openUsrMsg(txtToUse);
                            }
                            else if (tmpAr1[0]=="partnerTyping444")
                            {
                                updateTypingAreaVisiblity(false,true);
                            }
                            else
                            {
                               subjectFromListIndx=tmpAr1[0];
                               disableListTolkArea(false);
                               var rspContentAr=tmpAr1[1].split("*$*");
                               var msgTxt=rspContentAr[0];
                               var isOwner=parseInt(rspContentAr[1],10);
                               var lng=rspContentAr[2]
                               setTranslatedMsg(0,msgTxt,isOwner,lng);
                               if ($("#fromListSubjectTolkBox>div:last-child").hasClass("yourTolkItemElm"))
                                    setTitleText(partnerTolksTtl);
                               else setTitleText(youTolkTtl);
                               if (isOwner!=1)updateTypingAreaVisiblity(false,false);
                            }
                        }
                   }
                   setIsLockGetMsg(false);
                   getMsgInterval=4000;
                   window.clearTimeout(hndlGetMsg);
                   hndlGetMsg=window.setTimeout ("getMsg()",getMsgInterval);
                }
           });
        }
        else
        {
           getMsgInterval=100;
           window.clearTimeout(hndlGetMsg);
           hndlGetMsg=window.setTimeout ("getMsg()",getMsgInterval);
        }
    }
    function composeMsgHtml(txt,isOwner)
    {
        var htmlToRet="";
        if (isOwner==1)
        {
            htmlToRet="<div class='myTolkItemElm'><div><span class='spn tolkItmCorner'>"+
                      "<img src='./images/corner_lu.jpg' /></span><span class='spn' "+
                      "style='width:150px;border-top:2px solid black;height:6px;'></span>"+
                      "<span class='spn tolkItmCorner'><img style='vertical-align:top' "+
                      "src='./images/corner_ru.jpg' /></span><div class='dv' ><span>"+txt+
                      "</span></div><span class='spn tolkItmCorner'><img style='vertical-align:top;' "+
                      "src='./images/corner_ld.jpg' /></span><span class='spn' "+
                      "style='width:13px;border-bottom:2px solid black;height:6px;'></span>"+
                      "<span class='spn' style='width:23px;height:50px;'>"+
                      "<img style='position:relative;top:6px;' src='./images/stickItOut_l.jpg' /></span>"+
                      "<span class='spn' style='width:115px;border-bottom:2px solid black;height:6px;'></span>"+
                      "<span class='spn tolkItmCorner'><img style='vertical-align:top;' "+
                      "src='./images/corner_rd.jpg' /></span></div></div>";
        }
        else
        {
            htmlToRet="<div class='yourTolkItemElm'><div><span class='spn tolkItmCorner'>"+
                      "<img style='vertical-align:top;' src='./images/corner_lu.jpg' /></span>"+
                      "<span class='spn' style='width:150px;border-top:2px solid black;height:6px;'></span>"+
                      "<span class='spn tolkItmCorner'><img style='vertical-align:top' "+
                      "src='./images/corner_ru.jpg' /></span><div class='dv' ><span>"+txt+
                      "</span></div><span class='spn tolkItmCorner'><img style='vertical-align:top;' "+
                      "src='./images/corner_ld.jpg' /></span><span class='spn' "+
                      "style='width:115px;border-bottom:2px solid black;height:6px;'></span>"+
                      "<span class='spn' style='width:23px;height:50px;'><img style='position:relative;top:6px;' "+
                      "src='./images/stickItOut_r.jpg' /></span><span class='spn' "+
                      "style='width:13px;border-bottom:2px solid black;height:6px;'></span>"+
                      "<span class='spn tolkItmCorner'><img style='vertical-align:top;' "+
                      "src='./images/corner_rd.jpg' /></span></div></div>";
        }
        return htmlToRet;
    }
    function sendMsg(fromBoxNo)
    {
        var msg="";
        if (fromBoxNo==1)
        {
            msg=$("#newSubjectPostInput").attr("value");
//            $("#newSubjectPostInput").attr("value","");
        }
        else
        {
            msg=$("#fromListSubjectPostInput").attr("value");
  //          $("#fromListSubjectPostInput").attr("value","");
        }
        if (msg.replace(/ /g,"")!="" && msg!=msgInFromListTolkBox && msg!=msgInNewTolkBox)
        {
            setTitleText("tolkabout");
            postMsg(msg,fromBoxNo);
        }
    }
    function ditchDialogRequest(fromArea,isFinal)
    {
        if (fromArea==1)subjectChosenIndx=newSubjectIndx;
        else subjectChosenIndx=subjectFromListIndx;
        $.ajax({
          url: "ditchDialog.php?subjectIndx="+subjectChosenIndx+"&isFinal="+isFinal,
          success: function(data) {
            if (parseInt(data,10)!=1) window.setTimeout ("ditchDialogRequest("+fromArea+","+isFinal+")",100);
          }
        });
    }
    function tillDeathDoUsPart(fromArea)
    {
        if (fromArea==1)subjectChosenIndx=newSubjectIndx;
        else subjectChosenIndx=subjectFromListIndx;
        $.ajax({
          url: "tillDeathDoUsPart.php?subjectIndx="+subjectChosenIndx,
          success: function(data) {
          }
        });
    }
    function ditchResult(toContinue)
    {
           toggleDitchBox(0);
           if (toContinue)ditchDialogRequest(toDitchRequestFrom,1);
           else tillDeathDoUsPart(toDitchRequestFrom);
           setTitleText("tolkabout");
    }
    function setTitleText(titleText)
    {
        window.setTimeout ("setTitleTextGo('"+titleText+"')",100);
    }
    function setTitleTextGo(titleText)
    {
        document.title=titleText;
    }
    function closeUsrMsg()
    {
       doTheeFocus();
       $("#usrMsg").css("display","none");
       $("#curtain").removeClass("curtainDown").addClass("curtainUp");
    }
    function doTheeFocus()
    {
        if (focusId!="")document.getElementById(focusId).focus();
        clearTheeFocus();
    }
    function setTheeFocus(objId)
    {
        focusId=objId;
    }
    function clearTheeFocus()
    {
        focusId="";
    }
    function openUsrMsg(msgTxt,msgWidth)
    {
       if (msgWidth==3)$("#usrMsg").removeClass("msgToUser_Medium").removeClass("msgToUser_Large").addClass("msgToUser_ExtraLarge");
       else if (msgWidth==2)$("#usrMsg").removeClass("msgToUser_Medium").removeClass("msgToUser_ExtraLarge").addClass("msgToUser_Large");
       else $("#usrMsg").removeClass("msgToUser_Large").removeClass("msgToUser_ExtraLarge").addClass("msgToUser_Medium");
       $("#usrMsgTxt").html(msgTxt);
       $("#usrMsg").css("display","");
       $("#curtain").removeClass("curtainUp").addClass("curtainDown");
       $("#usrMsgBtn").focus();
    }
    function toggleDitchBox(toShow)
    {
        if (toShow)
        {
            $("#curtain").removeClass("curtainUp").addClass("curtainDown");
            $("#isDitchDlgBox").css("display","");
            $("#freeWillieBtn").focus();
        }
        else
        {
            $("#curtain").removeClass("curtainDown").addClass("curtainUp");
            $("#isDitchDlgBox").css("display","none");
        }

    }
    function scrollToBottom(obj)
    {
        window.setTimeout("goScroll('"+obj.id+"')",100)
    }
    function goScroll(objId)
    {
        var obj=document.getElementById(objId);
        obj.scrollTop=obj.scrollHeight+1000;
    }
    function chkTolkBoxOnEnter(obj,isInNew)
    {
        var emptyBoxMsg=msgInFromListTolkBox;
        if (isInNew) emptyBoxMsg=msgInNewTolkBox;
        if (obj.value==emptyBoxMsg)
        {
            obj.style.color="black";
            obj.value="";
        }
    }
    function forceTolkBoxTxt(obj,isInNew)
    {
        obj.style.color="gray"
        var emptyBoxMsg=msgInFromListTolkBox;
        if (isInNew) emptyBoxMsg=msgInNewTolkBox;
        obj.value=emptyBoxMsg;
    }
    function chkTolkBoxOnExit(obj,isInNew)
    {
        if (obj.value=="")
        {
            obj.style.color="gray"
            var emptyBoxMsg=msgInFromListTolkBox;
            if (isInNew) emptyBoxMsg=msgInNewTolkBox;
            obj.value=emptyBoxMsg;
        }
    }
    function serializeAr(tmpAr)
    {
        var rtStr="";
        for (jj=0;jj<tmpAr.length;jj++)
            if (tmpAr[jj]!="")
            {
                if (rtStr!="")rtStr+=",";
                rtStr+=tmpAr[jj];
            }
        return rtStr;
    }
    function slctLng()
    {

        lngSlctCode=$("#lngSlct").attr("value");

    /*
        siteTxtAr = new Array();

        resetAllSiteTxt();

        var siteTxtToTranslate=msgInNewTolkBox+"*.*"+msgInFromListTolkBox+"*.*"+sbjListChngdMsg+"*.*"+
            sbjListChngdReallyMsg+"*.*"+sbjListChngdPlentyMsg+"*.*"+sbjListItmMySlctTtl+"*.*"+sbjListItmMyDelTtl+"*.*"+
            sbjListItmMyTwtTtl+"*.*"+sbjAlreadyChosenMsg+"*.*"+sbjStartTolkMsg+"*.*"+sbjLostMsg+"*.*"+msgSendAgainMsg+"*.*"+
            sbjConLostMsg+"*.*"+ditchPartnerRequestTtl+"*.*"+ditchPartnerFinalTtl+"*.*"+partnerTolksTtl+"*.*"+
            youTolkTtl+"*.*"+sbjIncestTabooMsg+"*.*"+sbjMyLimitMsg+"*.*"+sbjTotalLimitMsg+"*.*"+delSbjNoGoMsg+"*.*"+
            sbjAreaHdrTxt+"*.*"+sbjInputPlaceHolder+"*.*"+sbjInputTtl+"*.*"+sbjAddBtnTxt+"*.*"+tolkBtnTxt+"*.*"+
            ditchBtnTxt+"*.*"+sbjDitchMsg+"*.*"+ditchBtnFreeTxt+"*.*"+ditchBtnIgnoreTxt+"*.*"+contBtnTxt+"*.*"+
            aboutLinkTxt+"*.*"+iDitchMsg+"*.*"+youDitchMsg+"*.*"+conversationShutDownMsg+"*.*"+isTypingMsgArea+"*.*"+
            fromTwitterMsgPart1+"*.*"+fromTwitterMsgPart2;

        if (lngSlctCode!="en") siteTxtToTranslate=siteTxtToTranslate.replace(/tolk/gi,"talk");

        siteTxtAr=siteTxtToTranslate.split("*.*");
        translateOne(0,siteTxtAr[0]);
        startPrgrsBar();
  */
    }
    function resetAllSiteTxt()
    {
        msgInNewTolkBox="start talking about your subject";
        msgInFromListTolkBox="start talking about subject from list";
        sbjStartTolkMsg="you can start talking about";
        msgSendAgainMsg="oy vey. just send the msg again.";
        sbjConLostMsg="connection with partner is lost";
        ditchPartnerRequestTtl="partner wants to leave";
        ditchPartnerFinalTtl="partner left";
        partnerTolksTtl="partner talks";
        youTolkTtl="you talk";
        sbjInputPlaceHolder="<?=$sbjInputPlaceHolder?>";
        sbjInputTtl="<?=$sbjInputTtl?>";
        sbjAddBtnTxt="<?=$sbjAddBtnTxt?>";
        tolkBtnTxt="<?=$tolkBtnTxt?>";
        ditchBtnTxt="<?=$ditchBtnTxt?>";
        sbjDitchMsg="<?=$sbjDitchMsg?>";
        ditchBtnFreeTxt="<?=$ditchBtnFreeTxt?>";
        ditchBtnIgnoreTxt="<?=$ditchBtnIgnoreTxt?>";
        contBtnTxt="<?=$contBtnTxt?>";
        aboutLinkTxt="<?=$aboutLinkTxt?>";
        iDitchMsg="you left the conversation";
        youDitchMsg="your partner left the conversation";
        conversationShutDownMsg="the conversation got broken. sorry"
        isTypingMsgArea="<?=$isTypingMsgArea?>";
        fromTwitterMsgPart1="put";
        fromTwitterMsgPart2="on the board. press here";
    }
    function setTranslatedMsg(isNewBox,txt,isOwner,lng)
    {
        var lngFrom = lng=="df" ? "" : lng;
        var lngTo = lngSlctCode=="df" ? "en" : lngSlctCode;

        if (lngFrom==lngTo || isOwner==1)
        {
            putMsgInBox(isNewBox,txt,isOwner);
            return;
        }
        google.language.translate(txt,lngFrom,lngTo,function(result) {
            if (!result.error) {
               putMsgInBox(isNewBox,result.translation,isOwner);
            }
        });
    }
    function putMsgInBox(isNewBox,txt,isOwner)
    {
           var boxId="fromListSubjectTolkBox";
           if (isNewBox) boxId="newSubjectTolkBox";
           var tolkBoxTxt=$("#"+boxId).html()+composeMsgHtml(txt,isOwner);
           $("#"+boxId).html(tolkBoxTxt);
           scrollToBottom(document.getElementById(boxId));
    }
    function andItShookYouToo(txt)
    {
      if (txt=="" || txt==" ") return false;
      else return true;
    }
    function updateTypingStatus(obj)
    {
        if (obj.id=="newSubjectPostInput" && newSubjectIsTypingStatus==0)
            newSubjectIsTypingStatus=1;
        else if (obj.id=="fromListSubjectPostInput" && fromListSubjectIsTypingStatus==0)
            fromListSubjectIsTypingStatus=1;
    }
    function updateTypingAreaVisiblity(isNewSubjectBox,toShow)
    {
        if (isNewSubjectBox)
        {
            if (toShow)document.getElementById("newSubjectIsTypingMsgArea").style.visibility="visible";
            else document.getElementById("newSubjectIsTypingMsgArea").style.visibility="hidden";
        }
        else
        {
            if (toShow)document.getElementById("fromListSubjectIsTypingMsgArea").style.visibility="visible";
            else document.getElementById("fromListSubjectIsTypingMsgArea").style.visibility="hidden";
        }
    }
    function moveOn()
    {
        $.ajax({url:"closeSession.php",
        async:false
        });
    }
</script>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-11593898-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</html>
