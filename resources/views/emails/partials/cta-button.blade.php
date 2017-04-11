@php
/**
 * Set up variables
 */

$alignment = (isset($alignment)) ? $alignment : 'left';
$link = (isset($link)) ? $link : action('PageController@index');
$text = (isset($text)) ? $text  : 'Submit'

@endphp
<table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
  <tbody>
    <tr>
      <td align="{{ $alignment }}">
        <table border="0" cellpadding="0" cellspacing="0">
          <tbody>
            <tr>
              <td> <a href="{{ $link }}" target="_blank">{{ $text }}</a> </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>