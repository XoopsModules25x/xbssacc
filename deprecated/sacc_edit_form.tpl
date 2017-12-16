<!-- SACC Edit Form Template. (c) 2005 A Kitson UK -->
<h4><{$lang_pagetitle}></h4>
<{$editForm.javascript}>
<form name="<{$editForm.name}>" action="<{$editForm.action}>" method="<{$editForm.method}>" <{$editForm.extra}>>
  <table class="outer" cellspacing="1">
    <tr>
    <th colspan="2"><{$editForm.title}></th>
    </tr>
    <!-- start of form elements loop -->
    <{foreach item=element from=$editForm.elements}>
      <{if $element.hidden != true}>
      <tr>
        <td class="head"><{$element.caption}></td>
        <td class="<{cycle values="even,odd"}>"><{$element.body}></td>
      </tr>
      <{else}>
      <{$element.body}>
      <{/if}>
    <{/foreach}>
    <!-- end of form elements loop -->
  </table>
</form>
<!-- do not remove the following copyright section -->
<div align="right"><br>SACC (c) 2005, <a href='http://akitson.bbcb.co.uk' title=<{$lang_copyright}> target='_blank'>A Kitson.</a> UK</div>
<!-- end of copyright section -->
