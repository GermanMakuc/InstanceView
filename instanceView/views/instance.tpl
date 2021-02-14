{if $instance}
<section class="box big" id="main_slider">
    <h2>
		    ID : {$idRaid} - {$nameMap}
        <br>
        Dificultad : {$instance[0]['difficulty']} - Encuentros : {$instance[0]['completedEncounters']}
    </h2>
<ul id="log_list">
  <li>
  <table width="100%">
    <tbody>
    <tr>
      <th>N°</th>
      <th>Cuenta</th>
      <th>Username</th>
      <th>Nivel</th>
      <th>Guild</th>
      <th>Facción</th>
    </tr>
    {foreach from=$characters item=value}
    <tr>
      <td>{$value.index}</td>
      <td>{$value.account}</td>
      <td>{$value.name}</td>
      <td>{$value.level}</td>
      <td>{$value.guild}</td>
      <td>{$value.faction}</td>
    </tr>
    {/foreach}
    </tbody>
  </table>
  </li>
</ul>
</section>
{/if}

{if $Boss}
<section class="box big" id="main_slider">

<h2>
		<img src="{$url}application/themes/admin/images/icons/black16x16/ic_list.png"/>
    Jefes Derrotados
</h2>

<ul>

  <li>
  <table width="100%">
    <tbody>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Nivel</th>
    </tr>
    {foreach from=$Boss item=value}
    <tr>
      <td>{$value.id}</td>
      <td><a href="https://wotlkdb.com/?npc={$value.id}/" target="_blank">{$value.name}</a></td>
      <td>{$value.maxlevel}</td>
    </tr>
    {/foreach}
    </tbody>
  </table>
  </li>
</ul>
</section>
{/if}

{if $RareElite}
<section class="box big" id="main_slider">

<h2>
		<img src="{$url}application/themes/admin/images/icons/black16x16/ic_list.png"/>
    Elites Raros Derrotados
</h2>

<ul>

  <li>
  <table width="100%">
    <tbody>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Nivel</th>
    </tr>
    {foreach from=$RareElite item=value}
    <tr>
      <td>{$value.id}</td>
      <td><a href="https://wotlkdb.com/?npc={$value.id}/" target="_blank">{$value.name}</a></td>
      <td>{$value.maxlevel}</td>
    </tr>
    {/foreach}
    </tbody>
  </table>
  </li>
</ul>
</section>
{/if}

{if $Elite}
<section class="box big" id="main_slider">

<h2>
		<img src="{$url}application/themes/admin/images/icons/black16x16/ic_list.png"/>
    Elites Derrotados
</h2>

<ul>

  <li>
  <table width="100%">
    <tbody>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Nivel</th>
    </tr>
    {foreach from=$Elite item=value}
    <tr>
      <td>{$value.id}</td>
      <td><a href="https://wotlkdb.com/?npc={$value.id}/" target="_blank">{$value.name}</a></td>
      <td>{$value.maxlevel}</td>
    </tr>
    {/foreach}
    </tbody>
  </table>
  </li>
</ul>
</section>
{/if}