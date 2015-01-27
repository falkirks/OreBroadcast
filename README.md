OreBroadcast
============
OreBroadcast is a simple PocketMine plugin which will broadcast when players find ores. More customization options are coming soon...

### Vein Detection
To detect ore veins, a simple flood fill algorithm is employed. This process is currently done synchronously and a little tick drop may be experienced on large veins. This process is only run once per vein per player.
