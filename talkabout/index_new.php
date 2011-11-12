<?php


//    $brwsrType=strtolower($_SERVER['HTTP_USER_AGENT']);
//    if (is_numeric(strpos($brwsrType,'iphone'))) header("Location:mIndex.php");

    session_start();

    $s=$_GET["s"];
    $sbjAreaHdrTxt="Click on a subject, start talking";
    $sbjInputPlaceHolder="add up to 5 subjects";
    $sbjInputTtl="add up to 5 subjects";

    $sbjAddBtnTxt="add";
    $tolkBtnTxt="talk";
    $ditchBtnTxt="leave";

    $sbjDitchMsg="your partner wants to leave the conversation";
    $ditchBtnFreeTxt="let him go";
    $ditchBtnIgnoreTxt="pretend not to hear";
    $contBtnTxt="go with the flow";
    $aboutLinkTxt="about";
    $isTypingMsgArea="your partner is typing a message";
    $fromTwitterMsgPart1="to add";
    $fromTwitterMsgPart2="to the list of subjects, press here";
?>
<!DOCTYPE html>
<html>
<head>
<title>talkabout</title>
<link rel="stylesheet" href="style1.css" />

</head>

<body onload="onLoad()" onunload="moveOn()">
<div id="tolkaboutHdr">
    <span id="logo">Talkabout</span>
    <span id="lngSlctContainer">
        <select id="lngSlct" tabindex=0 onchange="slctLng()"><option SELECTED value="df">Select Language</option><option value=separator disabled>&#8212;</option><option  value="af">Afrikaans</option><option  value="sq">Albanian</option><option  value="ar">Arabic</option><option  value="be">Belarusian</option><option  value="bg">Bulgarian</option><option  value="ca">Catalan</option><option  value="zh-CN">Chinese</option><option  value="hr">Croatian</option><option  value="cs">Czech</option><option  value="da">Danish</option><option  value="nl">Dutch</option><option  value="en">English</option><option  value="et">Estonian</option><option  value="tl">Filipino</option><option  value="fi">Finnish</option><option  value="fr">French</option><option  value="gl">Galician</option><option  value="de">German</option><option  value="el">Greek</option><option  value="ht">Haitian Creole</option><option  value="iw">Hebrew</option><option  value="hi">Hindi</option><option  value="hu">Hungarian</option><option  value="is">Icelandic</option><option  value="id">Indonesian</option><option  value="ga">Irish</option><option  value="it">Italian</option><option  value="ja">Japanese</option><option  value="ko">Korean</option><option  value="lv">Latvian</option><option  value="lt">Lithuanian</option><option  value="mk">Macedonian</option><option  value="ms">Malay</option><option  value="mt">Maltese</option><option  value="no">Norwegian</option><option  value="fa">Persian</option><option  value="pl">Polish</option><option  value="pt">Portuguese</option><option  value="ro">Romanian</option><option  value="ru">Russian</option><option  value="sr">Serbian</option><option  value="sk">Slovak</option><option  value="sl">Slovenian</option><option  value="es">Spanish</option><option  value="sw">Swahili</option><option  value="sv">Swedish</option><option  value="th">Thai</option><option  value="tr">Turkish</option><option  value="uk">Ukrainian</option><option  value="vi">Vietnamese</option><option  value="cy">Welsh</option><option  value="yi">Yiddish</option></select>
        </span>
</div>
<div id="shocker">
    <div class="shockerContent" >
        <span class="textEmphasize">anonymous conversation on anything you choose, for your eyes only, in your language</span>
    </div>
    <div class="shockerContent" >
        <span class="textEmphasize">no registration, no names, free of charge</span>
    </div>
</div>
<div id="comesFromADistance" onclick="toggleSbjFromTwitterMsg()">
</div>
<div id="tolkaboutBody" class="grdInMain">
    <div id="manageSubjectsArea">
        <div class="subjectsArea">
            <div id="newSubjectsKnockingArea" onclick="updateSubjectsFromKnockingArea()">
                <span></span>
            </div>
        </div>
        <div id="subjectsListCaption">
            <span><?=$sbjAreaHdrTxt?></span>
        </div>
        <div id="subjectsListContainer">
        </div>
        <div class="subjectsArea">
            <div>
                <input id="newSubjectInput" name="newSubjectInput" placeholder="<?=$sbjInputPlaceHolder?>"
                    title="<?=$sbjInputTtl?>" onkeyDown="return isAddNewSubject(event)"/>
                <script language="javascript">
/*
                    if (!("placeholder" in document.createElement("input")))
                        document.getElementById("newSubjectInput").focus();
*/
                </script>
                <input id="newSubjectAdd" type="button" value="<?=$sbjAddBtnTxt?>" onkeyDown="return isAddNewSubject(event)" onclick="addNewSubject()" />
            </div>
        </div>
    </div>
    <div id="manageTolkArea">
        <div class="tolkArea" id="listTolkArea" draggable="true">
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
        <div class="tolkArea" id="myTolkArea" draggable="true">
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
<div id="ftr" class="grdInMain">
    <ul>
        <li><a href="./about.html" id="aboutLink"><?=$aboutLinkTxt?></a></li>
    </ul>
</div>
<div id="btmMargin" class="grdInMain"></div>
 <div id="curtain"></div>
 <div id="prgrsBar"></div>
 <iframe id="insideFrm" name="insideFrm" ></iframe>
</body>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>

<script language="javascript" type="text/javascript">
    google.load("language", "1");

    var newSubjectNmAr= [];
    var newSubjectIndxAr = [];
    var newSubjectIndx="";
    var subjectFromListIndx="";
    var newSubjectIsTypingStatus=0;
    var fromListSubjectIsTypingStatus=0;
    var toDitchRequestFrom=0;
    var isLockAddNewSubject=false;
    var swIsLockGetMsg=false;
    var toUpdateSubjects=false;
    var doMaintenanceInterval=5000;
    var getMsgInterval=4000;
    //the three musketeers: three handles for three timeouts
    var hndlGetMsg;
    var hndlDoMaintenance;
    var hndlAddNewSubject;
    var focusId="";
    var curSbjListIndxs=[];
    var lngSlctCode="df";
    var msgInNewTolkBox="";
    var msgInFromListTolkBox="";
    var sbjListChngdMsg="";
    var sbjListChngdReallyMsg="";
    var sbjListChngdPlentyMsg="";
    var sbjListItmMySlctTtl="";
    var sbjListItmMyDelTtl="";
    var sbjListItmMyTwtTtl="";
    var sbjAlreadyChosenMsg="";
    var sbjStartTolkMsg="";
    var sbjLostMsg="";
    var msgSendAgainMsg="";
    var sbjConLostMsg="";
    var ditchPartnerRequestTtl="";
    var ditchPartnerFinalTtl="";
    var partnerTolksTtl="";
    var youTolkTtl="";
    var sbjIncestTabooMsg="";
    var sbjMyLimitMsg="";
    var sbjTotalLimitMsg="";
    var delSbjNoGoMsg="";
    var sbjAreaHdrTxt="";
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
    var fromTwitterMsgPart1="";
    var fromTwitterMsgPart2="";
    var s="<?=addslashes($s)?>";
    var isFinishedFirstUpdate=false;

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
       $("#newSubjectsKnockingArea").hide();
       adjustScrn();
       resetAllSiteTxt();
       $("#newSubjectPostInput").html(msgInNewTolkBox);
       $("#fromListSubjectPostInput").html(msgInFromListTolkBox);
       $(window).resize(function(){
            adjustScrn();
        });
        $("#newSubjectIsTypingMsgArea").css("visibility","hidden");
        $("#fromListSubjectIsTypingMsgArea").css("visibility","hidden");
        $("body").bind('click',function(){
            if ($("#shocker").css("display")!="none")$("#shocker").slideToggle("slow",function(){
                adjustScrn();
                document.getElementById("curtain").style.height=getDocHeight()+"px";
                document.getElementById("prgrsBar").style.height=getDocHeight()+"px";
            });
        });
/*
        var shockerContentMarginTop=parseInt($("#shocker").attr("offsetHeight"),10)/2-
            parseInt($("#shockerContent").attr("offsetHeight"),10)/2;
        $("#shockerContent").css("marginTop",shockerContentMarginTop+"px");
*/


    })
    function toggleSbjFromTwitterMsg()
    {
       $("#comesFromADistance").slideToggle("slow",function(){
           adjustScrn();
       });
    }
    function adjustScrn()
    {
       $("#tolkaboutBody").css("height",parseInt($("#manageTolkArea").attr("offsetHeight")+2,10)+"px");
       if (getWndHeight()>$("#btmMargin").attr("offsetTop"))
       {
            $("#btmMargin").css("height",parseInt(getWndHeight()-$("#btmMargin").attr("offsetTop"),10)+"px");
       }
       else
       {
            $("#btmMargin").css("height","0");
       }
    }
    function onLoad()
    {
       setNextRefreshOp(true);
       startNextRefreshOp();
       hndlGetMsg=window.setTimeout ("getMsg()",getMsgInterval);
       hndlDoMaintenance=window.setTimeout ("doMaintenance()",doMaintenanceInterval);
       disableMyTolkArea(true);
       disableListTolkArea(true);
       if (s!="")window.setTimeout("chkIfSbjFromTwitterInList()",1000);
       $("#tolkaboutBody").css("visibility","visible");
    }
    function chkIfSbjFromTwitterInList()
    {
        if (isFinishedFirstUpdate)
        {
               var liCol=document.getElementById("subjectsListContainer").getElementsByTagName("li");
               for (var jj=0;jj<liCol.length;jj++)
               {
                    var liTxt=liCol[jj].innerText ? liCol[jj].innerText : liCol[jj].textContent;
                    if (liTxt==s)
                    {
                        startTolking(liCol[jj]);
                        var msgToUser="Dear John Doe. just below to your right you can see a conversation going on, between you and another John Doe. just the two of you. when you had enough you can press the 'leave' button. this button doesn't automatically stops the conversation, only if you partner agrees to stop it. you can also add new subjects to the list and wait for another twitter user to join the uno on uno conversation. if it's a popular topic than you probably won't have to wait for long. have fun and press anywhere here to make this annoying text go away.";
//                        showSbjFromTwitterMsg(msgToUser);
                        return;

                    }
               }
               addNewSubject(s,true);
               var msgToUser="dear john doe. your subject was added to the subjects list on the left. if your subject is a popular one, than there are reasonable chances that you'll have a partner really soon. if not than you'll probably have to wait a while. don't count on our hosting skills, go somewhere else and watch for the tab (or window) name. we'll notify you when your subject is chosen. in the meantime you can press anywhere here to make this annoying text go away.";
  //             showSbjFromTwitterMsg(msgToUser);
        }
        else
            window.setTimeout("chkIfSbjFromTwitterInList()",1000);
    }
    function showSbjFromTwitterMsg(msgToUser)
    {
        msgToUser=msgToUser.replace(/\. /ig,".<br/>");
        $("#comesFromADistance").html(msgToUser);
        toggleSbjFromTwitterMsg();
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
    function setNextRefreshOp(isUpdateList)
    {
        toUpdateSubjects=isUpdateList;
    }
    function isNextRefreshOpUpdate()
    {
        return toUpdateSubjects;
    }
    function isLockGetMsg()
    {
        return swIsLockGetMsg;
    }
    function setIsLockGetMsg(isLock)
    {
        swIsLockGetMsg=isLock;
    }
    function startNextRefreshOp()
    {
        if (!isAddNewSubjectLocked())
        {
            setAddNewSubjectLock(true);
            if (isNextRefreshOpUpdate())updateSubjectsList();
            else getNewSubjectsCount();
            doMaintenanceInterval=5000;
        }
        else doMaintenanceInterval=100;

    }
    function getNewSubjectsCount()
    {
        $.getJSON("getNewSubjectsCount.php",function(data){
           var cnt=0;
           setAddNewSubjectLock(false);
           $.each(data,function(k,v){
                var toAdd=true;
               $.each(curSbjListIndxs,function (k1,v1){
                    if (v==v1) toAdd=false;
                    return toAdd;
                });
                if (toAdd)cnt++;
           });
           if (cnt>0)
           {
                var msgStr=sbjListChngdMsg;
                if (cnt>100) msgStr=sbjListChngdPlentyMsg;
                else if (cnt>10) msgStr=sbjListChngdReallyMsg;
                $("#newSubjectsKnockingArea>span").text(msgStr);
                $("#newSubjectsKnockingArea").slideDown();
                setTitleText(msgStr);
           }
            else
            {
                clrNewSubjectsMsgArea();
                setTitleText("tolkabout");
            }
        });
    }

    function clrNewSubjectsMsgArea()
    {
        $("#newSubjectsKnockingArea>span").text("");
        $("#newSubjectsKnockingArea").slideUp();
    }
    function updateSubjectsList()
    {
        $.getJSON("updateSubjectsList.php",function(data) {

            var listToHTML="<ul>";
            curSbjListIndxs=[];
           $.each(data,function(k,v){
                var subjectIndx=k;
                var subjectNm=v.nm;
                var isMine=v.isMine;
                var textElementAttr="";
                var itemClickBehavior="";
                var itemClassName="subjectsListItem";
                var imgClass="";
                curSbjListIndxs[curSbjListIndxs.length]=k;

                if (isMine=="1")
                {
                      textElementAttr=" title='"+sbjListItmMySlctTtl+"' ";
                      itemClickBehavior=" onclick='explainTheConsequencesOfChoosingYourOwnSubjectMsg()' ";
                      itemClassName="mySubjectItem";
                      imgClass="tolkSubjectOptionItem hideMe";
                }
                else
                {
                     itemClickBehavior=" onclick='startTolking(this)' ";
                     itemClassName="yourSubjectItem";
                     imgClass="hideMe";
                }

                listToHTML+="<li class='"+itemClassName+"' "+itemClickBehavior+" onmouseover='toggleOptions(this,1);'"+
                            "onmouseout='toggleOptions(this,0);'><span"+textElementAttr+
                            " class='subjectListItmLn'>"+subjectNm+"</span><input type='hidden' value='"+
                            subjectIndx+"'/><span class='subjectListItmLn rightAlign'><img class='"+imgClass+
                            "' onmouseover='chngImg(this,1)' onmouseout='chngImg(this,2)' "+
                            " src='./images/trash_unchosen.ico' title='"+sbjListItmMyDelTtl+
                            "' onclick='delSubject(event);'/>"+
                            "<img class='"+imgClass+"' onmouseover='chngImg(this,1)' onmouseout='chngImg(this,2)' "+
                            "src='./images/twitter_unchosen.ico' title='"+sbjListItmMyTwtTtl+
                            "' onclick='tweetSubject(event);'/>"+
                            "</span></li>";

           });
           listToHTML+="</ul>";
            $("#subjectsListContainer").html(listToHTML);
           scrollToBottom(document.getElementById("subjectsListContainer"));
           setTitleText("talkabout");
           clrNewSubjectsMsgArea();
           setNextRefreshOp(false);
           setAddNewSubjectLock(false);
           isFinishedFirstUpdate=true;
        });
    }
    function startTolking(obj)
    {
        if (subjectFromListIndx!="")
        {
            openUsrMsg(sbjAlreadyChosenMsg,3);
        }
        else
        {
            subjectFromListIndx=obj.getElementsByTagName("input")[0].value;
            disableListTolkArea(false);
            $.ajax({
              url: 'startTolking.php?subjectIndx='+subjectFromListIndx,
              success: function(data) {
                if (data=="1")
                {
                    var boxContent=sbjStartTolkMsg+" "+obj.firstChild.innerHTML;
                    postMsg(boxContent,2);
                }
                else
                {
                    subjectFromListIndx="";
                    openUsrMsg(sbjLostMsg);
                    setNextRefreshOp(true);
                }
              }
            });
        }
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
    function doMaintenance()
    {
        var sbjToKeepAlive=serializeAr(newSubjectIndxAr)+"*"+subjectFromListIndx;
        $.ajax({
          url: "doMaintenance.php?sbjToKeepAlive="+sbjToKeepAlive+"&newSubjectIsTyping="+newSubjectIsTypingStatus+
               "&fromListSubjectIsTyping="+fromListSubjectIsTypingStatus+"&newSubjectIndx="+newSubjectIndx+
               "&subjectFromListIndx="+subjectFromListIndx,
          error:function(data) {
                window.clearTimeout(hndlDoMaintenance);
                doMaintenanceInterval=100;
                hndlDoMaintenance=window.setTimeout ("doMaintenance()",doMaintenanceInterval);
          },
          success: function(data) {
                startNextRefreshOp();
                window.clearTimeout(hndlDoMaintenance);
                hndlDoMaintenance=window.setTimeout ("doMaintenance()",doMaintenanceInterval);
                if (data.indexOf("typing in new registered")==1 && newSubjectIsTypingStatus==1)newSubjectIsTypingStatus=2;
                if (data.indexOf("typing in from list registered")==1 &&
                    fromListSubjectIsTypingStatus==1  )fromListSubjectIsTypingStatus=2;
          }
        });
    }
    function setAddNewSubjectLock(isLock)
    {
        isLockAddNewSubject=isLock;
    }
    function isAddNewSubjectLocked()
    {
        return isLockAddNewSubject;
    }
    function registerNewSubject(newSbjctIndx)
    {
        newSubjectNmAr[newSubjectNmAr.length]=$("#newSubjectInput").attr("value");
        newSubjectIndxAr[newSubjectIndxAr.length]=newSbjctIndx;
        $("#newSubjectInput").attr("value","");
        setNextRefreshOp(true);
        setAddNewSubjectLock(false);
        startNextRefreshOp();
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
    function explainTheConsequencesOfChoosingYourOwnSubjectMsg()
    {
        openUsrMsg(sbjIncestTabooMsg)
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
    function addNewSubject(s,isFromTwitter)
    {
        var newSbj=s ? s : $("#newSubjectInput").attr("value");
        if (isAddNewSubjectLocked() || newSbj=="")
        {
            if (isAddNewSubjectLocked())
            {
                window.clearTimeout(hndlAddNewSubject);
                hndlAddNewSubject=window.setTimeout("addNewSubject()",1000);
//              openUsrMsg("oopps! something happened. wait a second or two before trying to add your new subject again");
            }
        }
        else
        {
            setAddNewSubjectLock(true);
            var urlStr="addNewSubject.php?newSubjectInput="+newSbj+"&isFromTwitter="+(isFromTwitter ? 1 : 0);
            $.ajax({url: urlStr,
                    error: function(data) {
                        setAddNewSubjectLock(false);},
                    success: function(data) {
                        dataAr=data.split("*&*");
                        if (parseInt(dataAr[0],10)>4)
                        {
                            setAddNewSubjectLock(false);
                            openUsrMsg(sbjMyLimitMsg);
                            $("#newSubjectInput").attr("value","");
                        }
                        else if (parseInt(dataAr[1],10)>499)
                        {
                            setAddNewSubjectLock(false);
                            openUsrMsg(sbjTotalLimitMsg);
                            $("#newSubjectInput").attr("value","");
                        }
                        else registerNewSubject(dataAr[2]);
                    }
        });

        }
    }
    function updateSubjectsFromKnockingArea()
    {
        setNextRefreshOp(true);
        startNextRefreshOp();

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
    function isAddNewSubject(e)
    {
        if (e.keyCode==13 || e.which==13)
        {
            addNewSubject();
            return false;
        }
        else return true;
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
    function chngImg(obj, opt) {

        if (opt == 1) {
            obj.src=obj.src.replace(/unchosen/,"chosen");
            obj.style.cursor = "pointer";
        }
        else {
            obj.src=obj.src.replace(/chosen/,"unchosen");
            obj.style.cursor = "default";
        }
    }
    function delSubject(e) {
          var obj1=e.srcElement ? e.srcElement : e.target;
          var sSubjectIndx= obj1.parentNode.parentNode.getElementsByTagName("input")[0].value;
          if (e.stopPropagation)e.stopPropagation();
          else e.cancelBubble=true;
        $.ajax ({url:"delSubject.php?subjectIndx="+sSubjectIndx,success:function(data){
             if (parseInt(data,10)==1)
             {
                 for (ii=0;ii<newSubjectIndxAr.length;ii++)
                 {
                     if (parseInt(newSubjectIndxAr[ii],10)==parseInt(sSubjectIndx))
                     {
                        newSubjectIndxAr.splice(ii,1);
                        break;
                     }
                 }

                 setNextRefreshOp(true);
                 startNextRefreshOp();
             }
             else openUsrMsg (delSbjNoGoMsg);
        }

        })
    }
    function tweetSubject(e)
    {
          if (e.stopPropagation)e.stopPropagation();
          else e.cancelBubble=true;
          var obj1=e.srcElement ? e.srcElement : e.target;
          var sSubject=obj1.parentNode.parentNode.firstChild.innerHTML;
          localStorage.setItem("subjectToTweet",sSubject);
          window.open ("twitter_login.php","Twitter_Sendin","width:500px;height:500px;")
    }

    function toggleOptions(obj,opt) {

//       if (opt==0) $(".tolkSubjectOptionItem").removeClass("showMe").addClass("hideMe");
//        else $(".tolkSubjectOptionItem").removeClass("hideMe").addClass("showMe");

        var slctd=obj.querySelectorAll(".tolkSubjectOptionItem");
        var classNmsStr="tolkSubjectOptionItem hideMe";
        if (opt==1) classNmsStr="tolkSubjectOptionItem showMe";
        for (var jj=0;jj<slctd.length;jj++)
            slctd[jj].className = classNmsStr;
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
    function translateOne(indx,txt)
    {
        google.language.translate(txt, "en", lngSlctCode, function(result) {
            if (!result.error) {

                var trTxt=result.translation;

                if (andItShookYouToo(trTxt))
                {
                    switch(indx)
                    {
                        case 0:
                            msgInNewTolkBox=trTxt;
                            break;
                        case 1:
                            msgInFromListTolkBox=trTxt;
                            break;
                        case 2:
                            sbjListChngdMsg=trTxt;
                            break;
                        case 3:
                            sbjListChngdReallyMsg=trTxt;
                            break;
                        case 4:
                            sbjListChngdPlentyMsg=trTxt;
                            break;
                        case 5:
                            sbjListItmMySlctTtl=trTxt;
                            break;
                        case 6:
                            sbjListItmMyDelTtl=trTxt;
                            break;
                        case 7:
                            sbjListItmMyTwtTtl=trTxt;
                            break;
                        case 8:
                            sbjAlreadyChosenMsg=trTxt;
                            break;
                        case 9:
                            sbjStartTolkMsg=trTxt;
                            break;
                        case 10:
                            sbjLostMsg=trTxt;
                            break;
                        case 11:
                            msgSendAgainMsg=trTxt;
                            break;
                        case 12:
                            sbjConLostMsg=trTxt;
                            break;
                        case 13:
                            ditchPartnerRequestTtl=trTxt;
                            break;
                        case 14:
                            ditchPartnerFinalTtl=trTxt;
                            break;
                        case 15:
                            partnerTolksTtl=trTxt;
                            break;
                        case 16:
                            youTolkTtl=trTxt;
                            break;
                        case 17:
                            sbjIncestTabooMsg=trTxt;
                            break;
                        case 18:
                            sbjMyLimitMsg=trTxt;
                            break;
                        case 19:
                            sbjTotalLimitMsg=trTxt;
                            break;
                        case 20:
                            delSbjNoGoMsg=trTxt;
                            break;
                        case 21:
                            sbjAreaHdrTxt=trTxt;
                            break;
                        case 22:
                            sbjInputPlaceHolder=trTxt;
                            break;
                        case 23:
                            sbjInputTtl=trTxt;
                            break;
                        case 24:
                            sbjAddBtnTxt=trTxt;
                            break;
                        case 25:
                            tolkBtnTxt=trTxt;
                            break;
                        case 26:
                            ditchBtnTxt=trTxt;
                            break;
                        case 27:
                            sbjDitchMsg=trTxt;
                            break;
                        case 28:
                            ditchBtnFreeTxt=trTxt;
                            break;
                        case 29:
                            ditchBtnIgnoreTxt=trTxt;
                            break;
                        case 30:
                            contBtnTxt=trTxt;
                            break;
                        case 31:
//                            aboutLinkTxt=trTxt;
                            break;
                        case 32:
                            iDitchMsg=trTxt;
                            break;
                        case 33:
                            youDitchMsg=trTxt;
                            break;
                        case 34:
                            conversationShutDownMsg=trTxt;
                            break;
                        case 35:
                            isTypingMsgArea=trTxt;
                            break;
                        case 36:
                            fromTwitterMsgPart1=trTxt;
                            break;
                        case 37:
                            fromTwitterMsgPart2=trTxt;
                            break;
                    }
                }
                if (indx==37)
                {

//                    $("#curtain").removeClass("curtainDown").addClass("curtainUp");

                    forceTolkBoxTxt(document.getElementById("newSubjectPostInput"),1);
                    forceTolkBoxTxt(document.getElementById("fromListSubjectPostInput"),0);
                    $("#subjectsListCaption span").html(sbjAreaHdrTxt);
                    if ("placeholder" in document.getElementById("newSubjectInput"))
                        $("#newSubjectInput").attr("placeholder",sbjInputPlaceHolder);
                    $("#newSubjectInput").attr("title",sbjInputTtl);
                    $("#newSubjectAdd").attr("value",sbjAddBtnTxt);
                    $(".tolkBtn").attr("value",tolkBtnTxt);
                    $(".ditchBtn").attr("value",ditchBtnTxt);
                    $("#ditchMsgTxt").html(sbjDitchMsg);
                    $("#freeWillieBtn").attr("value",ditchBtnFreeTxt);
                    $("#ignoreBtn").attr("value",ditchBtnIgnoreTxt);
                    $("#usrMsgBtn").attr("value",contBtnTxt);
                    $("#aboutLink").html(aboutLinkTxt);
                    $("#newSubjectIsTypingMsgArea span").html(isTypingMsgArea);
                    $("#fromListSubjectIsTypingMsgArea span").html(isTypingMsgArea);
                    $("#fromTwitterMsgPart1").html(fromTwitterMsgPart1);
                    $("#fromTwitterMsgPart2").html(fromTwitterMsgPart2);
                }
                else
                {
                    translateOne(indx+1,siteTxtAr[indx+1]);
                }
            }
            else
            {
                translateOne(indx,siteTxtAr[indx]);
            }
        });

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
    function startPrgrsBar()
    {
        if (!$("#prgrsBar").hasClass("curtainDown"))$("#prgrsBar").addClass("curtainDown");
        $("#prgrsBar").css("width","100%");
        $("#prgrsBar").animate({width:"0"},10000);
    }
    function resetAllSiteTxt()
    {
        msgInNewTolkBox="start talking about your subject";
        msgInFromListTolkBox="start talking about subject from list";
        sbjListChngdMsg="things have changed";
        sbjListChngdReallyMsg="things have really changed";
        sbjListChngdPlentyMsg="wow!!! how things have changed";
        sbjListItmMySlctTtl="this is your subject";
        sbjListItmMyDelTtl="delete";
        sbjListItmMyTwtTtl="tweet";
        sbjAlreadyChosenMsg=
            "you have already chosen a subject. you can't choose another one unless you leave the old one";
        sbjStartTolkMsg="you can start talking about";
        sbjLostMsg="sorry, but this one is lost";
        msgSendAgainMsg="oy vey. just send the msg again.";
        sbjConLostMsg="connection with partner is lost";
        ditchPartnerRequestTtl="partner wants to leave";
        ditchPartnerFinalTtl="partner left";
        partnerTolksTtl="partner talks";
        youTolkTtl="you talk";
        sbjIncestTabooMsg="you can't choose your own subject";
        sbjMyLimitMsg="you can't add more than 5 subjects";
        sbjTotalLimitMsg="sorry. can't handle more subjects. try again shortly.";
        delSbjNoGoMsg="you can't delete a subject while conversing";
        sbjAreaHdrTxt="<?=$sbjAreaHdrTxt?>";
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
    function shockAway()
    {
        $("#shocker").css("display","none");
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
