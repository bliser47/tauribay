@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-md-12">
             <div class="panel panel-default gdkprules">
                 <h2 class="text-center"> Bliser GDKP Szabályzat </h2>
                 <div class="gdkpsection col-md-12">
                     <div class="col-md-2 col-sm-4 col-xs-12 text-center">
                        <h3> Loot </h3>
                        <img src="{{ URL::asset('img/gdkp/loot.jpg') }}" alt="Loot"/>
                     </div>
                     <div class="col-md-10 col-sm-8 col-xs-12 gdkpsectionright">
                         <p>
                            Loot-nak számít minden olyan <span class="q4">Epic</span> item ami vagy Trash vagy Boss-ból származik.
                            Minden Loot licitálásra kerül a felsorolt kezdőárakkal. A kezdőárak alsó határa a
                            <a class="itemToolTip q3 listview-cleartext" href="http://mop-shoot.tauri.hu/?item=94289">Haunting Spirit</a> jelenlegi gazdasági értékéhez viszonyulnak.
                         </p>
                         <table id="gdkpLoot">
                             <tr>
                                <th>Item típus</th>
                                <th>Minimum licit</th>
                                <th>Licit lépcső</th>
                              </tr>
                              <tr>
                                <td>Trash loot</td>
                                <td>3000</td>
                                <td>1000</td>
                              </tr>
                              <tr>
                                 <td>Fegyver/Off-hand</td>
                                 <td>5000</td>
                                 <td>1000</td>
                              </tr>
                              <tr>
                                <td>Trinket</td>
                                <td>5000</td>
                                <td>1000</td>
                              </tr>
                              <tr>
                                <td>Token</td>
                                <td>5000</td>
                                <td>1000</td>
                              </tr>
                                <td>Minden más loot</td>
                                <td>3000</td>
                                <td>1000</td>
                              </tr>
                         </table>
                     </div>
                 </div>
                 <div class="gdkpsection col-md-12">
                    <div class="col-md-2 col-sm-4 col-xs-12 text-center">
                         <h3> Licit </h3>
                         <img src="{{ URL::asset('img/gdkp/licit.jpg') }}" alt="Licit"/>
                     </div>
                     <div class="col-md-10 col-sm-8 col-xs-12 gdkpsectionright">
                        <p>A boss/trash halála után <i>Master Loot</i> által a raidvezető az összes itemet saját magához illeti. Majd egyenként nyílt licitálásra kerülnek az itemek: raid chaten.
                        <p>A résztvevők a meghirdetett minimum licittől (beleértve a minimum értéket) licitálnak raid chaten (figyelembe véve a licit lépcsőt).</p>
                        <p>Amennyiben azonos licit érkezik, itt természetesen a korábbi licit nyer előnyt.</p>
                        <h4> <span class="help-block">Hibák</span></h4>
                        <p>Helytelen licit lépcső használat esetén a licit <u>nem elfogadott</u>: pld Jóska utolsó licitje 4000, Árpi 4500-es licitje hibás mivel a licitlépcső 1000 arany. Az itemet Jóska viszi.</p>
                        <p><b>Visszavonás esetén</b> minden item újralicitálásra kerül! A visszavonó játékos pedig egy 500 goldos büntetőt fizet (ami a potból levonódik). </p>
                     </div>
                 </div>
                 <div class="gdkpsection col-md-12">
                     <div class="col-md-2 col-sm-4 col-xs-12 text-center">
                         <h3> Pot </h3>
                         <img src="{{ URL::asset('img/gdkp/pot.jpg') }}" alt="Pot"/>
                     </div>
                     <div class="col-md-10 col-sm-8 col-xs-12 gdkpsectionright">
                        <p> A Pot az összes megnyert licit értékeinek összege. Minden boss halála után és ennek itemjeinek kiosztása után az összes játékos potból való részesülése újraszámolódik. <i>Ennek működése és célja később kerül felvilágosításra.</i></p>
                        <p> Amennyiben bekövetkezik az az időpont amelyik meglett hirdetve mint: <b>Raid end</b>, bármelyik játékos távozhat a raidből az ő részét követelve a Potból. Ez a játékos ilyenkor
                        az általa megölt bossokon lévő licitek összegeinek az 1/25-ét kapja részesül.</p>
                        <h4> Példa </h4>
                        <p> A meghirdetett <i>Raid end</i> 23:00, valamint a raid épp megölte <a href="http://mop-shoot.tauri.hu/?npc=69427" style="font-family: Verdana, sans-serif;">Dark Animus</a>-t. Pár játékosnak távoznia kell ezért az addig összegyűlt pot 1/25-vel távoznak fejenként.</p>
                        <p> A raid tovább halad 23 játékossal és 2 újabb játékos csatlakozik a raidhez. <a href="http://mop-shoot.tauri.hu/?npc=68397" style="font-family: Verdana, sans-serif;">Lei Shen</a> halála után a 23 játékos az eredeti pot 1/25-vel távozik fejenként, viszont
                        a két új játékos aki előbb csatlakozott: az az Iron Qon, Twin Consorts és Lei Shen lootjaiból megnyert licit értékeinek összegének 1/25-vel távozik.</p>
                        <p> <b> Figyelem! </b> Azok a játékosok akik a meghirdetett <i>Raid end</i> előtt távoznak: nem részesülhetnek a potból. </p>
                     </div>
                 </div>
                 <div class="gdkpsection col-md-12">
                      <div class="col-md-2 col-sm-4 col-xs-12 text-center">
                          <h3> Elvárás </h3>
                          <img src="{{ URL::asset('img/gdkp/elvaras2.jpg') }}" alt="Elvárás"/>
                      </div>
                      <div class="col-md-10 col-sm-8 col-xs-12 gdkpsectionright">
                            <p>A meghirdetett elvárások mellett a raiden bizonyos elvárások érintik a részvevőket. A raid leader fenntarja a jogot, hogy ezen elvárások alapján
                            bizonyos szankciókat alkalmazzon a játékosokra.</p>
                            <p>Amennyiben a raid leader úgy véli, hogy a játékos hátráltatja a GDKP haladását, a játékos kikerül a raidből. Ilyenkor a játékos <b>jogosan részesül</b> az általa megölt bossokon lévő licitek összegeinek az 1/25-hez!</p>
                            <p>Egyszerű példa: egy játékos alulteljesít valamint számára releváns itemekre nem hajlandó licitálni valamilyen okból. Ez a játékos természetesen ellentmond a GDKP alapi értelmének így a raid leader úgy dönt, hogy megválik a részvevőtől. A raid Tortosnál tart, így a játékos az előző 3 bossból lévő licitek összegeinek az 1/25-vel távozik.</p>
                      </div>
                  </div>
             </div>
        </div>
    </div>
@stop