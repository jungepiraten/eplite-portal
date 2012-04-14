{include file="header.tpl"}
<form action="{$PHP_SELF}" method="post" class="form-inline">
	<fieldset>
		<input type="text" name="padName" />
		<button onClick="location.href='{$root}' + $(this).parent().find('[name=padName]').val(); return false;" class="btn btn-primary">Pad anlegen</button>
	</fieldset>
</form>

<table class="table table-striped table-bordered">
<thead>
	<tr>
		<th>&nbsp;</th>
		<th>Pad</th>
		{if $showPadOptions}
			<th>&nbsp;</th>
		{/if}
	</tr>
</thead>
<tbody>
{foreach item=pad from=$pads}
	<tr>
		<td>
			{if $pad.isPublic}
				{if $showPadOptions}<a href="{$root}?padName={$pad.pad|escape:url}&amp;do=setPublic&amp;public=0">{/if}
				<i class="icon-eye-open"></i>
				{if $showPadOptions}</a>{/if}
			{else}
				{if $showPadOptions}<a href="{$root}?padName={$pad.pad|escape:url}&amp;do=setPublic&amp;public=1">{/if}
				<i class="icon-eye-close"></i>
				{if $showPadOptions}</a>{/if}
			{/if}
			{if $pad.isProtected}<i class="icon-lock"></i>{/if}
		</td>
		<td><a href="{$root}{$pad.pad|escape:url}">{$pad.pad|escape:html}</a></td>
		{if $showPadOptions}
			<td>
				<a href="#" onclick="$('#setPasswordModal input[name=padName]').val('{$pad.pad|escape:html}'); $('#setPasswordModal').modal(); return false;" class="btn btn-mini">Passwort setzen</a>
				<a href="{$root}?padName={$pad.pad|escape:url}&amp;do=delete" class="btn btn-danger btn-mini deletePad">Pad löschen</a>
			</td>
		{/if}
	</tr>
{/foreach}
</tbody>
</table>

<div class="modal" id="setPasswordModal" style="display:none;">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">×</a>
		<h3>Passwort setzen</h3>
	</div>
	<form action="{$root}" method="post" class="form-horizontal modal-body">
		<input type="hidden" name="do" value="setPassword" />
		<input type="hidden" name="padName" value="" />

		<div class="control-group">
			<label for="password" class="control-label">Passwort:</label>
			<p class="controls">
				<input type="text" name="password" />
			</p>
		</div>
	</form>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Abbrechen</a>
		<button onclick="$(this).parent().parent().find('form').submit()" class="btn btn-primary">Passwort setzen</button>
	</div>
</div>

<div class="modal" id="deleteModal" style="display:none;">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">×</a>
		<h3>Pad löschen</h3>
	</div>
	<p class="modal-body">
		Pad wirklich löschen?
	</p>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Abbrechen</a>
		<a href="" class="btn btn-danger" id="delLink">Löschen</a>
	</div>
</div>

{literal}
<script type="text/javascript">
<!--

$("a.deletePad").click(function (event) {
	event.stopImmediatePropagation();
	$("#deleteModal").children(".modal-footer").children("#delLink").attr("href", $(this).attr("href"));
	$("#deleteModal").modal();
	return false;
});

//-->
</script>
{/literal}
{include file="footer.tpl"}

