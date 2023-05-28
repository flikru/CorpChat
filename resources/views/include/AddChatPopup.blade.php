<div id="popup_add_chat">
    <p>Контент</p>
    <span id="popup_close_chat" class="close">ₓ</span>
</div>
<div id="myOverlay"></div>

<style>
    #popup_add_chat {
        width: 298px; height: 218px;
        padding: 18px 9px;
        border-radius: 4px;
        background: #fafafa;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        margin: auto;
        display: none;
        opacity: 0;
        z-index: 38;
        text-align: center;
    }
    #myModal #popup_close_chat {
         width: 21px; height: 21px;
         position: absolute;
         font-size: 29px;
         top: 1px; right: 11px;
         cursor: pointer;
         display: block;
     }
    #myOverlay {
        z-index: 37;
        position: fixed;
        background: rgba(0,0,0,.7);
        width: 100%; height: 100%;
        top: 0; left: 0;
        cursor: pointer;
        display: none;
    }
</style>
