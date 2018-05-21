# LobbySystem
LobbySystem alles einstellbar / A LobbySystem with all Configs

--------------------
## German:

### Instalieren:
- Das plugin in den Plugin ordners deines Servers kopieren. 
- Den Server restarten. 
- Fertig!

-


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
