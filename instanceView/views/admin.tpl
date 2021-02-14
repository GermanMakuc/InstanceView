<section class="box big" id="main_slider">
    <h2>
		<img src="{$url}application/themes/admin/images/icons/black16x16/ic_list.png"/>
		Ingrese el ID de instancia
    </h2>

<ul id="slider_list">
    <li>
    <form action="admin/viewInstance" method="POST">
            <input id="instanceId" type="text" name="instanceId" placeholder="Búsqueda por ID">
            <input type="hidden" name="{$tokenName}" value="{$tokenValue}">
            <input type="submit" value="Buscar">
    </form>
    </li>
</ul>
</section>

{if $lastInstance}
<section class="box big" id="main_slider">

<h2>
		<img src="{$url}application/themes/admin/images/icons/black16x16/ic_list.png"/>
    Últimas 5 Instancias
</h2>

<ul>

  <li>
  <table width="100%">
    <tbody>
    <tr>
      <th>ID Instancia</th>
      <th>Dificultad</th>
      <th>Mapa</th>
    </tr>
    {foreach from=$lastInstance item=value}
    <tr>
      <td>{$value.id}</td>
      <td>{$value.difficulty}</td>
      <td>{$value.name}</td>
    </tr>
    {/foreach}
    </tbody>
  </table>
  </li>
</ul>
</section>
{/if}