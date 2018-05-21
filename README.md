# LobbySystem
LobbySystem alles einstellbar / A LobbySystem with all Configs --- by iTzFreeHD

--------------------
### Download .phar:
- [![Download .phar](https://poggit.pmmp.io/ci/Hyroxing/LobbySystem/LobbySystem+-+iTzFreeHDv2)](https://poggit.pmmp.io/ci/Hyroxing/LobbySystem/LobbySystem+-+iTzFreeHDv2)
--------------------
## German:

### Achtung:
- Bevor du das Plugin beginnst einzustellen lese dir den Aspeckt [Wichtig](#wichtig) durch!

### Instalieren:
- Das plugin in den Plugin ordners deines Servers kopieren. 
- Den Server restarten. 
- Fertig!

### Beschreibung:
- Das LobbySystem ist ein Plugin welches den Benutzern das einstellen der Items einfacher macht. So kann man jedes einzelne Item einzelnt in der Config einstellen. Ausserdem lässt sich über die "action" funktion etwas ausführen z.B. ```action:  cmd:gamemode 1 ```.

### Eigenschaften:
- [x] Alles einstellbar
- [x] No-Hunger
- [x] No-Damage
- [x] No-Drop
- [x] No-Void
- [x] No-Permission-MSG

### Hilfe?
- Wenn du hilfe brachst schau dir den Aspekt [Guide](#guide) oder [Wichtig](#wichtig) einmal genau an.
- Wenn das oben genannte dir nocht hilft kannst du uns über den discord kontaktieren Link: https://discord.gg/Kna6D9f
------------
### Wichtig:
- Bitte beachte das ```Teleporter:``` gleich wie ```name: §6Teleporter``` nur das bei dem "name" Farbsymbole gesetzt werden dürfen. Falls das nicht der fall ist können fehler entstehen. In folgenden Versionen wird dieses Problem behoben sein!

#### Richtig:
```php
Main:
  Teleporter:
    name: §6Teleporter
    id: "345:0:1"
    slot: 3
    action: menu:Info
    permissions: ""
```
#### Falsch:
```php
Main:
  TP:
    name: §6Teleporter
    id: "345:0:1"
    slot: 3
    action: menu:Info
    permissions: ""
```
-----
### Guide:

- Actions:

| Actions  | Benutzung | Bedeutung |
| ------------- | ------------- |------------- | 
| msg | action: 'msg: Hello World' | Sendet dem Spieler eine Nachricht |
| cmd | action: 'cmd:freeparticle' | Führt einen befehl für den Spieler aus |
| menu | action: 'menu:Main' | Öffnet das jeweils angegebene Menu |
| tp | action: 'tp:X:Y:Z' | Teleportiert den Spieler zu den angegebenen Kordinaten |
| tp | action: 'tp:spawn' | Teleportiert den Spieler zum Weltpawm |

### Adons:
- Particle -> Soon
- PlayerHider -> Soon
-----
## Items.yml
```php
---
Build-Perms: lobby.build
noHunger: true
noDamage: true
noDrop: true
noVoid: true
NoPermission: §cDu hast keine Rechte
JoinMenu: Main
Main:
  Teleporter:
    name: §6Teleporter
    id: "345:0:1"
    slot: 3
    action: menu:Info
    permissions: ""
  Build:
    name: §6Build
    id: "2:0:1"
    slot: 5
    action: cmd:build
    permissions: lobby.build
  Tools:
    name: §6Tools
    id: "396:0:1"
    slot: 8
    action: cmd:freeparticle
    permissions: ""
Info:
  Info:
    name: §bInfo
    id: "340:0:1"
    slot: 0
    action: 'msg: §bGehe auf github.com/Hyroxing um mehr Infos zu den Einstellungen
      zu erhalten'
    permissions: lobby.info
  Spawn:
    name: §6Spawn
    id: "378:0:1"
    slot: 4
    action: tp:spawn
    permissions: ""
  Exit:
    name: §cExit
    id: "351:1:1"
    slot: 8
    action: menu:Main
    permissions: ""
...
```
