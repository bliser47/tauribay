@extends('layouts.default')
@section('content')
    <div class="row">
        <h1> Lvl 10 alatti karik akik nem tudjuk hova tartoznak </h1>
        <div class="table-responsive row col-sm-12">
            <table class="table">
                @foreach ( $lowLevels as $lowLevel )
                    <tr>
                        <td>
                            <form method="POST">
                                <input name="name" type="hidden" value="{{ $lowLevel->name  }}"/>
                                {!! csrf_field() !!}
                                <table class="table table-bordered">
                                    <tr>
                                        <td> {{ $lowLevel->name }} </td>
                                        <td>
                                           <input type="number" name="level" placeholder="level"/>
                                        </td>
                                        <td>
                                            <select name="faction" required="required">
                                                <option value="0">?</option>
                                                <option value="1">Horde</option>
                                                <option value="2">Alliance</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="race">
                                                <option value="0">?</option>
                                                <option value="1">Human</option>
                                                <option value="2">Orc</option>
                                                <option value="3">Dwarf</option>
                                                <option value="4">Night Elf</option>
                                                <option value="5">Undead</option>
                                                <option value="6">Tauren</option>
                                                <option value="7">Gnome</option>
                                                <option value="8">Troll</option>
                                                <option value="9">Goblin</option>
                                                <option value="10">Blood Elf</option>
                                                <option value="11">Draenei</option>
                                                <option value="22">Worgen</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="class">
                                                <option value="0">?</option>
                                                <option value="1">Warrior</option>
                                                <option value="2">Paladin</option>
                                                <option value="3">Hunter</option>
                                                <option value="4">Rogue</option>
                                                <option value="5">Priest</option>
                                                <option value="6">Death Knight</option>
                                                <option value="7">Shaman</option>
                                                <option value="8">Mage</option>
                                                <option value="9">Warlock</option>
                                                <option value="10">Druid</option>
                                            </select>
                                        </td>
                                        <td> <button> Mentés </button></td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@stop