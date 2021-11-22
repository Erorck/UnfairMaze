<html>

<head>
	<title>UnfairMaze</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/jugar.css">
	<link rel="stylesheet" href="../css/FondoMedieval.css">
	<script type="text/javascript" src="js/libs/jquery/jquery-2.1.4.min.js"></script>
	<script type="text/javascript" src="js/libs/three/three.js"></script>
	<script type="text/javascript" src="js/libs/three/three2.js"></script>

	<script type="text/javascript" src="js/libs/three/MTLLoader.js"></script>
	<script type="text/javascript" src="js/libs/three/FBXLoader.js"></script>
	<script type="text/javascript" src="js/libs/three/OBJLoader.js"></script>

	<script type="text/javascript" src="js/libs/three/inflate.min.js"></script>


	<script type="text/javascript">

		var scene;
		var camera;
		var renderer;
		var controls;
		var estaPausado = false;

		//variables globales para la funcion
		var contador = 0;
		var posInicial = -4; //posicion para el primer fantasma
		var posInicial2 = 4; //posicion para el segundo fantasma
		var distancia = 8;

		var mixersP = [];
		var mixersE = [];

		//Coordenadas del jugador del modo de juego principal
		var mainModePlayer = [[1,2]];

		//Coordenadas de las paredes del modo de juego principal
		var mainModeWalls = [
			[0, 0], [0, 1], [0, 2], [0, 3], [0, 4], [0, 5], [0, 6], [0, 7], [0, 8], [0, 9], [0, 10], [0, 11], [0, 12], [0, 13], [0, 14], [0, 15], [0, 16], [0, 17], [0, 18], [0, 19],
			[1, 0], [1, 19],
			[2, 0], [2, 2], [2, 5], [2, 13], [2, 14], [2, 16], [2, 17], [2, 19],
			[3, 0], [3, 5], [3, 19],
			[4, 0], [4, 5], [4, 7], [4, 8], [4, 9], [4, 11], [4, 12], [4, 14], [4, 17], [4, 19],
			[5, 0], [5, 2], [5, 3], [5, 19],
			[6, 0], [6, 6], [6, 8], [6, 10], [6, 13], [6, 14], [6, 16], [6, 19],
			[7, 0], [7, 2], [7, 3], [7, 4], [7, 8], [7, 19],
			[8, 0], [8, 6], [8, 8], [8, 9], [8, 10], [8, 14], [8, 16], [8, 19],
			[9, 0], [9, 2], [9, 14], [9, 16],
			[10, 0], [10, 7], [10, 9], [10, 11], [10, 19],
			[11, 0], [11, 2], [11, 4], [11, 7], [11, 9], [11, 11], [11, 13], [11, 15], [11, 16], [11, 17], [11, 19],
			[12, 0], [12, 2], [12, 4], [12, 19],
			[13, 0], [13, 4], [13, 7], [13, 8], [13, 10], [13, 11], [13, 15], [13, 17], [13, 19],
			[14, 0], [14, 2], [14, 14], [14, 15], [14, 19],
			[15, 0], [15, 2], [15, 14], [15, 15], [15, 19],
			[16, 0], [16, 19],
			[17, 0], [17, 1], [17, 2], [17, 3], [17, 4], [17, 5], [17, 6], [17, 7], [17, 8], [17, 9], [17, 10], [17, 11], [17, 12], [17, 13], [17, 14], [17, 15], [17, 16], [17, 17], [17, 18], [17, 19]
		];

		//Coordenadas de los cubos del piso del modo de juego principal
		var mainModeFloor = [
			[2, 3], [2, 7], [2, 9], [2, 11],
			[3, 2],
			[4, 16],
			[6, 17],
			[7, 6],
			[8, 13],
			[9, 3], [9, 17],
			[11, 5],
			[12, 13],
			[13, 5],
			[14, 11],
			[15, 6], [15, 9], [15, 17],
			[16, 12],
			[1, 0], [1, 1], [1, 2], [1, 3], [1, 4], [1, 5], [1, 6], [1, 7], [1, 8], [1, 9], [1, 10], [1, 11], [1, 12], [1, 13], [1, 14], [1, 15], [1, 16], [1, 17], [1, 18],
			[2, 1], [2, 4], [2, 6], [2, 8], [2, 10], [2, 12], [2, 15], [2, 16], [2, 17], [2, 18],
			[3, 1], [3, 3], [3, 4], [3, 6], [3, 7], [3, 8], [3, 9], [3, 10], [3, 11], [3, 12], [3, 13], [3, 14], [3, 15], [3, 16], [3, 17], [3, 18],
			[4, 1], [4, 2], [4, 3], [4, 4], [4, 6], [4, 10], [4, 13], [4, 15], [4, 18],
			[5, 1], [5, 4], [5, 5], [5, 6], [5, 7], [5, 8], [5, 9], [5, 10], [5, 11], [5, 12], [5, 13], [5, 14], [5, 15], [5, 16], [5, 17], [5, 18],
			[6, 1], [6, 2], [6, 3], [6, 4], [6, 5], [6, 7], [6, 9], [6, 11], [6, 12], [6, 13], [6, 15], [6, 18],
			[7, 1], [7, 5], [7, 7], [7, 9], [7, 10], [7, 11], [7, 12], [7, 13], [7, 14], [7, 15], [7, 16], [7, 17], [7, 18],
			[8, 1], [8, 2], [8, 3], [8, 4], [8, 5], [8, 7], [8, 11], [8, 12], [8, 17], [8, 18],
			[9, 1], [9, 4], [9, 5], [9, 6], [9, 7], [9, 8], [9, 9], [9, 10], [9, 11], [9, 12], [9, 13], [9, 18], [9, 19],
			[10, 1], [10, 2], [10, 3], [10, 4], [10, 5], [10, 6], [10, 7], [10, 8], [10, 10], [10, 12], [10, 13], [10, 14], [10, 15], [10, 16], [10, 17], [10, 18],
			[11, 1], [11, 3], [11, 6], [11, 8], [11, 10], [11, 12], [11, 14], [11, 18],
			[12, 1], [12, 3], [12, 4], [12, 5], [12, 6], [12, 7], [12, 8], [12, 9], [12, 10], [12, 11], [12, 12], [12, 14], [12, 15], [12, 16], [12, 17], [12, 18],
			[13, 1], [13, 2], [13, 3], [13, 6], [13, 9], [13, 12], [13, 13], [13, 14], [13, 16], [13, 18],
			[14, 1], [14, 3], [14, 4], [14, 5], [14, 6], [14, 7], [14, 8], [14, 9], [14, 10], [14, 12], [14, 13], [14, 16], [14, 17], [14, 18],
			[15, 1], [15, 3], [15, 4], [15, 5], [15, 7], [15, 8], [15, 10], [15, 11], [15, 12], [15, 13], [15, 16], [15, 18],
			[16, 1], [16, 2], [16, 3], [16, 4], [16, 5], [16, 6], [16, 7], [16, 8], [16, 9], [16, 10], [16, 11], [16, 12], [16, 13], [16, 14], [16, 15], [16, 16], [16, 17], [16, 18]
		];

		var mainModeWin = [[9, 19]];

		//Coordenadas de los corazones del modo de juego principal
		var mainModeHearts = [[1, 12], [7, 9], [12, 18]];

		//Coordenadas de los relojes del modo de juego principal
		var mainModeClocks = [[1, 5], [5, 9], [14, 3]];

		//Coordenadas de los regalos del modo de juego principal
		var mainModeGifts = [[3, 10], [13, 12], [2, 18]];

		//Coordenadas de las paredes atravesables del modo de juego principal
		var mainModeFakeWalls = [
			[1, 11], 
			[5, 14],
			[6, 9],
			[7, 10],
			[8, 15], 
			[9, 1], [9, 15],
			[11, 8], [11, 18],
			[12, 4], [12, 11], [12, 17],
			[14, 4]];

		//Coordenadas de los picos del modo de juego principal
		var mainModeSpikes = [
			[2, 12], [2, 15],
			[3, 3], [3, 16],
			[5, 11],
			[5, 11],
			[6, 3],
			[8, 12],
			[9, 5], [9, 13],
			[11, 12], 
			[14, 12],
			[15, 7], [15, 16], 
			[16, 9], [16, 11]];

		//Coordenadas de las paredes aplastantes del modo de juego principal
		var mainModeCrushingWalls = [
			[1, 17],
			[4, 10],
			[6, 7], [6, 15],
			[8, 2],
			[10, 10], [10, 7],
			[12, 3],
			[13, 9], [13, 18],
			[15, 1],
			[16, 6]];

		//Coordenadas de los señuelos del modo de juego principal
		var mainModeMannequins = [[2, 4], [2, 8],];
		var mainModeEnemies = [[6, 11], [15, 4], [16, 17]];

		//Coordenadas de las decoraciones del modo de juego principal
		var mainModeDecorators = [
			[2, 3], [2, 7], [2, 9], [2, 11],
			[3, 2],
			[4, 16],
			[6, 17],
			[7, 6],
			[8, 13],
			[9, 3], [9, 17],
			[11, 5],
			[12, 13],
			[13, 5],
			[14, 11],
			[15, 6], [15, 9], [15, 17],
			[16, 12],
		];

		//Direcciones de los assets
		modelPaths = {
			escenario: "escenario",
			items: "items",
			spikes: "spikes",
			mannequins: "mannequins",
			enemies: "enemies",
			decorators: "decorators"
		};

		modelPaths.escenario = [
			{
				path: "assets/MainMode/Bosque/Escenario/", obj: "cuboHojas.obj", mtl: "cuboHojas.mtl",
				path2: "assets/MainMode/Bosque/Escenario/", obj2: "cuboLodo.obj", mtl2: "cuboLodo.mtl"
			},
			{
				path: "assets/MainMode/Medieval/Escenario/", obj: "cuboLadrillo.obj", mtl: "cuboLadrillo.mtl",
				path2: "assets/MainMode/Medieval/Escenario/", obj2: "cuboLadrilloPiso.obj", mtl2: "cuboLadrilloPiso.mtl"
			},
			{
				path: "assets/MainMode/Futurista/Escenario/", obj: "cuboFuturista.obj", mtl: "cuboFuturista.mtl",
				path2: "assets/MainMode/Futurista/Escenario/", obj2: "cuboFuturistaPiso.obj", mtl2: "cuboFuturistaPiso.mtl"
			}];

		modelPaths.items = [
			{ path: "assets/Items/Corazon/", obj: "corazon.obj", mtl: "corazon.mtl" },
			{ path: "assets/Items/Reloj/", obj: "reloj.obj", mtl: "reloj.mtl" },
			{ path: "assets/Items/Regalo/", obj: "Regalo.obj", mtl: "Regalo.mtl" }

		];

		modelPaths.spikes = [
			{ path: "assets/Trampas/Picos/", obj: "picosViejos.obj", mtl: "picosViejos.mtl" },
			{ path: "assets/Trampas/Picos/", obj: "picosModernos.obj", mtl: "picosModernos.mtl" },
			{ path: "assets/Trampas/Picos/", obj: "picosModernos.obj", mtl: "picosModernos.mtl" }
		];

		modelPaths.mannequins = [
			{ path: "assets/Enemigo/guardian/", obj: "guardian_Bosque.obj", mtl: "guardian_Bosque.mtl" },
			{ path: "assets/Enemigo/fantasma/", obj: "fantasmita.obj", mtl: "fantasmita.mtl" },
			{ path: "assets/Enemigo/alien/", obj: "alien.obj", mtl: "alien.mtl" }
		]

		modelPaths.decorators = [
			{ path: "assets/Decoraciones/Bosque/", obj: "fuente_tiki.obj", mtl: "fuente_tiki.mtl" },
			{ path: "assets/Decoraciones/Medieval/", obj: "estatua_medieval.obj", mtl: "estatua_medieval.mtl" },
			{ path: "assets/Decoraciones/Futurista/", obj: "flor_alienigena_2.obj", mtl: "flor_alienigena_2.mtl" }
		]

		//Material y mesh de prueba
		var geometriaCubo = new THREE.BoxGeometry(10, 10, 10);
		var geometriaPrismaRectangular = new THREE.BoxGeometry(5, 10, 5);
		var materialCubo = new THREE.MeshLambertMaterial({ color: new THREE.Color(0, 0.6, 0.7), wireframe: false });
		var materialCuboInv = new THREE.MeshLambertMaterial({
			color: new THREE.Color(0, 0.6, 0.7), opacity: 0.0,
			transparent: true
		});
		var materialCuboTransp = new THREE.MeshLambertMaterial({
			color: new THREE.Color(0, 0.6, 0.7), opacity: 0.2, transparent: true
		});


		//Objecto maestro de todos los assets en la escena
		var objects = {
			winCondition: "winCondition",
			boxes: "boxes",
			floorBoxes: "floorBoxes",
			fakeBoxes: "fakeBoxes",
			decorators: "decorators",
			spikes: "spikes",
			crushWalls: "crushWalls",
			mannequins: "mannequins",
			enemies: "enemies",
			items: "items"
		};

		//Arreglo de cada tipo de asset
		objects.winCondition = [];
		objects.boxes = [];
		objects.floorBoxes = [];
		objects.fakeBoxes = [];
		objects.decorators = [];
		objects.crushWalls = [];
		objects.mannequins = [];
		objects.enemies = [];
		objects.spikes = [];
		objects.items = {
			hearts: "hearts",
			clocks: "clocks",
			gifts: "gifts"
		};

		//Arreglo de cada tipo de item
		objects.items.hearts = [];
		objects.items.clocks = [];
		objects.items.gifts = [];

		//Index de que escenario esta seleccionado
		// [0] -> Bosque
		// [1] -> Medieval
		// [2] -> Futurista
		var sceneIndex = 1;

		var clock;
		var timer = []; //TIMERS TRAMPAS
		var timer2 = new THREE.Clock(true);//TIMER ENEMIGOS
		var deltaTime;
		var slowerEnemies = false;
		var enemySpeed = 1;
		var keys = {};
		var players = [];
		var playerAlive = true;
		var numPlayers = 2;

		// Clase que nos permite "lanzar" un rayo hacia diferentes direcciones
		// y detectar si el "rayo" colisiona con un objeto en esa direccion
		var rayCaster = [];
		var collisionObjects = [];
		var collisionEnemies = [];
		var collisionTrapsSpikes = [];
		var collisionTrapsWalls = [];
		var winCollision = [];
		var collisionItems = [];
		var isWorldReady = [false, false, false];


		$(document).ready(function () {

			setupScene();
			$("#VictoryScreen").hide();
			$("#DeathScreen").hide();

			//GENERADOR DE NUMERO ENTERO ALEATORIO DADO UN RANGO
			function getRandomInt(min, max) {
				min = Math.ceil(min);
				max = Math.floor(max);
				return Math.floor(Math.random() * (max - min + 1)) + min;
			}

			//FUNCION PARA SABER SI UNA POSICION EN EL LABERINTO ESTA OCUPADA DADO UN ARREGLO
			function positionExists(position, list) {
				//var exists = false;

				for (var i = 0; i < list.length; i++) {
					listItem = list[i];
					if (position[0] == listItem[0] && position[1] == listItem[1]) {
						return true;
					}
				}
				return false;
			}

			//FUNCION DE ACOMODO DE ORIENTACION PARA LOS ASSETS
			function rotateObjects(objects, walls) {
				//Recorremos el arreglo de assets dado como parametro
				objects.forEach(function (asset) {
					//Creamos un arreglo con todas las poisciones alrededor del asset
					var surrounding = [
						[asset.coordZ - 1, asset.coordX - 1],
						[asset.coordZ - 1, asset.coordX],
						[asset.coordZ - 1, asset.coordX + 1],
						[asset.coordZ, asset.coordX - 1],
						[asset.coordZ, asset.coordX + 1],
						[asset.coordZ + 1, asset.coordX - 1],
						[asset.coordZ + 1, asset.coordX],
						[asset.coordZ + 1, asset.coordX + 1]
					];

					//Verificamos si las posiciones estan ocupadas y
					//vaciamos los resultados en un arreglo booleano
					var surroundingExists = [];
					surrounding.forEach(function (obj) {
						surroundingExists.push(positionExists(obj, walls));
					});

					var front = 4; //Hacia que objeto esta mirando el asset
					var frontP;
					var rotations = 1;//Cuantas veces ha sido rotado el objeto 90°
					var randomNumber = getRandomInt(0, 3);//Numero aleatorio entre 0-3

					//Dependiendo del numero, se decidira a hacía cual posicion esta viendo
					/*
					---------------------------------
					---------------------------------
					---       ---       ---       ---
					---   0   ---   1   ---   2   ---
					---       ---       ---       ---
					---------------------------------
					---       ---       ---       ---
					---   3   --- asset ---   4   ---
					---       ---       ---       ---
					---------------------------------
					---       ---       ---       ---
					---   5   ---   6   ---   7   ---
					---       ---       ---       ---
					---------------------------------
					---------------------------------
					
					Inicialmente, todos las decoraciones miran hacia la posicion 4,
					es decir, hacía la derecha
					
					*/
					switch (randomNumber) {
						case 1:
							front = 6;
							break;

						case 2:
							front = 3;
							break;

						case 3:
							front = 1;
							break;

						default:
							front = 4;
							break;
					}

					frontP = front;

					//Se rota el asset un numero de veces igual al numero aleatorio
					asset.model.rotation.y -= THREE.Math.degToRad(90) * randomNumber;

					//Si el asset en cuestion se encuentra con un obstaculo frente a él, este
					//se girara 90° en el sentido de las manecillas del reloj, hasta que no haya nada en frente suyo
					while (surroundingExists[front]) {
						asset.model.rotation.y -= THREE.Math.degToRad(90);
						rotations++;

						switch (rotations) {
							case 1:
								front = frontP;
								break;

							case 2:
								switch (front) {

									case 6:
										front = 3;
										break;

									case 3:
										front = 1;
										break;

									case 1:
										front = 4;
										break;

									default:
										front = 6;
										break;
								}
								break;

							case 3:
								switch (front) {

									case 6:
										front = 1;
										break;

									case 3:
										front = 4;
										break;

									case 1:
										front = 6;
										break;

									default:
										front = 3;
										break;
								}
								break;

							case 4:
								switch (front) {

									case 6:
										front = 4;
										break;

									case 3:
										front = 6;
										break;

									case 1:
										front = 3;
										break;

									default:
										front = 1;
										break;
								}
								break;

							default:
								break;
						}

					}


				})
			}

			//FUNCION DE POSICIONAMIENTO EN X,Z parametros(String tipo de mesh, asset, arreglo de posiciones)
			function positionObject(object, mesh = new THREE.Mesh(geometriaCubo, materialCubo), positions) {
				
				var asset;
				var asset2;

				//POSICIONAMIENTO DE ASSETS DE PRUEBA - CUBOS DE COLOR SOLIDO
				if (object == "testbox") {

					// var geometria = new THREE.BoxGeometry(10, 10, 10);
					// var material = new THREE.MeshLambertMaterial({ color: new THREE.Color(0, 0.6, 0.7), wireframe: false });

					cubo = new THREE.Mesh(geometriaCubo, materialCubo);

					cubo.position.z = -40;
					cubo.position.x = -44;
					cubo.position.y = 5;

					positions.forEach(function (element) {
						asset = cubo.clone();

						asset.coordZ = element[0];
						asset.coordX = element[1];
						asset.position.x += asset.coordX * 10;
						asset.position.z += asset.coordZ * 10;
						objects.boxes.push(asset);
					});

					objects.boxes.forEach(function (box) {
						collisionObjects.push(box);
					});
				}

				if (object == "testboxThin") {


					cubo = new THREE.Mesh(geometriaPrismaRectangular, materialCubo);

					cubo.position.z = -40;
					cubo.position.x = -44;
					cubo.position.y = 5;

					positions.forEach(function (element) {
						asset = cubo.clone();

						asset.coordZ = element[0];
						asset.coordX = element[1];
						asset.position.x += asset.coordX * 10;
						asset.position.z += asset.coordZ * 10;
						objects.boxes.push(asset);
					});

					objects.boxes.forEach(function (box) {
						scene.add(box);
						collisionObjects.push(box);
					});
				}

				//POSICIONAMIENTO DE PAREDES
				if (object == "wallblock" || object == "floorblock" || object == "fakebox") {
					positions.forEach(function (element) { //POR CADA POSICION SE HARA UNA COPIA DEL ASSET

						asset = mesh.clone();

						asset.position.z = -40; //SE POSICIONA EL ASSET EN EL ORIGEN (ESQUINA SUPERIOR IZQUIERDA DEL LABERINTO)
						asset.position.x = -44;

						if (object == "wallblock" || object == "fakebox")
							asset.position.y = -0.1;
						else
							asset.position.y = -10;

						asset.coordZ = element[0]; //INDICES DE POSICIÓN 
						asset.coordX = element[1];
						asset.position.x += asset.coordX * 10; //POSICIONAMIENTO DE ACUERDO AL INDICE
						asset.position.z += asset.coordZ * 10;
						if (object == "wallblock")
							objects.boxes.push(asset); // SE AGREGA AL OBJETO MAESTRO EN SU RESPECTIVO ARREGLO
						else if (object == "floorblock")
							objects.floorBoxes.push(asset);
						else
							objects.fakeBoxes.push(asset);
					});

				}


				//POSICIONAMIENTO DE ITEMS
				if (object == "hearts" || object == "clocks" || object == "gifts") {
					positions.forEach(function (element) {
						asset = mesh.clone();
						var assetColl = new THREE.Mesh(geometriaPrismaRectangular, materialCuboInv);

						asset.position.z = assetColl.position.z = -40;
						asset.position.x = assetColl.position.x = -44;
						asset.position.y = 3;
						assetColl.position.y = 5;

						asset.coordZ = assetColl.coordZ = element[0];
						asset.coordX = assetColl.coordX = element[1];
						asset.position.x += asset.coordX * 10;
						asset.position.z += asset.coordZ * 10;
						assetColl.position.x = asset.position.x;
						assetColl.position.z = asset.position.z;

						assetColl.taken = false;
						assetColl.model = asset;

						if (object == "hearts") {
							assetColl.itemType = "heart";
							objects.items.hearts.push(assetColl);
						}

						if (object == "clocks") {
							assetColl.itemType = "clock";
							objects.items.clocks.push(assetColl);
						}

						if (object == "gifts") {
							assetColl.itemType = "gift";
							objects.items.gifts.push(assetColl);
						}
					});
				}

				//////////////////////////////////
				//POSICIONAMIENTO DE TRAMPAS//////
				//////////////////////////////////
				//PICOS
				if (object == "trap_spike") {
					positions.forEach(function (element) {
						asset = mesh.clone();
						var assetColl = new THREE.Mesh(geometriaCubo, materialCuboInv);

						asset.position.z = assetColl.position.z = -40;
						asset.position.x = assetColl.position.x = -44;
						asset.position.y = -7;
						assetColl.position.y = 5;

						asset.coordZ = assetColl.coordZ = element[0];
						asset.coordX = assetColl.coordX = element[1];
						asset.position.x += asset.coordX * 10;
						asset.position.z += asset.coordZ * 10;
						assetColl.position.x = asset.position.x;
						assetColl.position.z = asset.position.z;
						// var spikeObj = {
						// 	activated:"activated",
						// 	mesh: "mesh"
						// }

						// spikeObj.activated = false;
						// spikeObj.mesh = asset;

						assetColl.activated = false;
						assetColl.model = asset;

						objects.spikes.push(assetColl);
					});
				}

				//POSICIONAMIENTO DE PAREDES APLASTANTES
				if (object == "crushWall") {
					positions.forEach(function (element) {
						asset = mesh.clone(); //PARED 1
						asset2 = mesh.clone(); //PARED 2
						var assetColl = new THREE.Mesh(geometriaPrismaRectangular, materialCuboInv);//COLISION

						//Poscionamos los assets en el origen
						asset.position.z = asset2.position.z = assetColl.position.z = -40;
						asset.position.x = asset2.position.x = assetColl.position.x = -44;
						asset.position.y = asset2.position.y = -0.1;
						assetColl.position.y = 5;

						//Coordenadas de las cajas de arriba, abajo, izquierda y derecha
						var surrounding = [
							[element[0] - 1, element[1]],
							[element[0], element[1] - 1],
							[element[0], element[1] + 1],
							[element[0] + 1, element[1]]
						];

						//Verificamos si hay una pared en esas cuatro posiciones
						var surroundingExists = [];
						surrounding.forEach(function (obj) {
							surroundingExists.push(positionExists(obj, mainModeWalls));
						});

						assetColl.coordZ = element[0];
						assetColl.coordX = element[1];

						//Existen paredes arriba y abajo
						if (surroundingExists[0]) {
							asset.coordZ = element[0] - 1;
							asset.coordX = element[1];
						}

						if (surroundingExists[3]) {
							asset2.coordZ = element[0] + 1;
							asset2.coordX = element[1];
						}

						//Existen paredes a la izquierda y derecha
						if (surroundingExists[1]) {
							asset.coordZ = element[0];
							asset.coordX = element[1] - 1;
						}

						if (surroundingExists[2]) {
							asset2.coordZ = element[0];
							asset2.coordX = element[1] + 1;
						}

						//Posicionamos las 2 paredes y la colision en sus lugares
						asset.position.x += asset.coordX * 10;
						asset.position.z += asset.coordZ * 10;

						asset2.position.x += asset2.coordX * 10;
						asset2.position.z += asset2.coordZ * 10;

						assetColl.position.x += assetColl.coordX * 10;
						assetColl.position.z += assetColl.coordZ * 10;

						//Definimos que la trampa no esta activada y 
						//linkeamos las paredes a la colision
						assetColl.activated = false;
						assetColl.wall_1 = asset;
						assetColl.wall_2 = asset2;


						assetColl.itemType = "crushWall";
						objects.crushWalls.push(assetColl);


					});
				}



				//POSICIONAMIENTO DE SALIDA DEL LABERINTO				
				if (object == "winCond") {
					positions.forEach(function (element) {

						var assetColl = new THREE.Mesh(geometriaCubo, materialCuboInv);
						//var assetColl = mesh.clone();

						assetColl.position.z = -40;
						assetColl.position.x = -44;

						assetColl.coordZ = element[0];
						assetColl.coordX = element[1];

						assetColl.position.x += assetColl.coordX * 10;
						assetColl.position.z += assetColl.coordZ * 10;

						assetColl.activated = false;
						assetColl.model = asset;

						objects.winCondition.push(assetColl);
					});
				}

				// POSICIONAMIENTO DE MANIQUIS
				/*if (object == "mannequins") {
									positions.forEach(function (element) {
										asset = mesh.clone();
				
										asset.coordZ = element[0];
										asset.coordX = element[1];
										asset.position.x += asset.coordX * 10;
										asset.position.z += asset.coordZ * 10;
										objects.mannequins.push(asset);
									});
								}
				
								if (object == "enemies") {
									positions.forEach(function (element) {
										asset = mesh.clone();
				
										asset.coordZ = element[0];
										asset.coordX = element[1];
										asset.position.x += asset.coordX * 10;
										asset.position.z += asset.coordZ * 10;
										objects.enemies.push(asset);
									});
					}
				*/

				if (object == "enemies" || object == "mannequins") {
					positions.forEach(function (element) {

						asset = mesh.clone();
						var assetColl = new THREE.Mesh(geometriaPrismaRectangular, materialCuboTransp);

						asset.position.z = -40;
						asset.position.x = -44;
						asset.position.y = 0;
						assetColl.position.y = 5;

						asset.coordZ = assetColl.coordZ = element[0];
						asset.coordX = assetColl.coordX = element[1];
						asset.position.x += asset.coordX * 10;
						asset.position.z += asset.coordZ * 10;

						assetColl.model = asset;
						asset.add(assetColl);

						if (object == "enemies")
							objects.enemies.push(assetColl);

						if (object == "mannequins")
							objects.mannequins.push(assetColl);
					});
				}


				//POSICIONAMIENTO DE DECORACIONES
				if (object == "decorators") {
					positions.forEach(function (element) {
						asset = mesh.clone();
						var assetColl = new THREE.Mesh(geometriaCubo, materialCuboInv);

						asset.position.z = assetColl.position.z = -40;
						asset.position.x = assetColl.position.x = -44;
						assetColl.position.y = 5;

						asset.coordZ = assetColl.coordZ = element[0];
						asset.coordX = assetColl.coordX = element[1];
						asset.position.x += asset.coordX * 10;
						asset.position.z += asset.coordZ * 10;
						assetColl.position.x = asset.position.x;
						assetColl.position.z = asset.position.z;

						assetColl.model = asset;

						objects.decorators.push(assetColl);
					});
				}
			}


			//CARGA DE LAS PAREDES
			loadOBJWithMTL(modelPaths.escenario[sceneIndex].path, modelPaths.escenario[sceneIndex].obj, modelPaths.escenario[sceneIndex].mtl, (object) => {

				positionObject("wallblock", object, mainModeWalls);//PAREDES REGULARES

				positionObject("fakebox", object, mainModeFakeWalls);//PAREDES ATRAVESABLES

				positionObject("crushWall", object, mainModeCrushingWalls);//PAREDES ATRAVESABLES

				positionObject("winCond", object, mainModeWin);//SALIDA DEL LABERINTO

				objects.boxes.forEach(function (block) { //AÑADIR CADA ELEMENTO A LAS ESCENA Y AL ARREGLO DE OBJETOS CON COLISION
					scene.add(block);
					collisionObjects.push(block);
				});

				//AÑADIR PAREDES ATRAVESABLES A LA ESCENA SIN COLISION
				objects.fakeBoxes.forEach(function (block) {
					scene.add(block);
				});

				//AÑADIR PAREDES APLASTANTES A LA ESCENA
				objects.crushWalls.forEach(function (block) {
					scene.add(block);
					scene.add(block.wall_1);
					scene.add(block.wall_2);
					collisionTrapsWalls.push(block);
					collisionObjects.push(block.wall_1);
					collisionObjects.push(block.wall_2);
				});

				//AÑADIR SALIDAS DEL LABERINTO - CONDICIONES DE VICTORIA
				objects.winCondition.forEach(function (block) {
					scene.add(block);
					winCollision.push(block);
				});

			});

			//CARGA DEL PISO
			loadOBJWithMTL(modelPaths.escenario[sceneIndex].path2, modelPaths.escenario[sceneIndex].obj2, modelPaths.escenario[sceneIndex].mtl2, (object) => {

				positionObject("floorblock", object, mainModeFloor);//PAREDES REGULARES

				objects.floorBoxes.forEach(function (block) { //AÑADIR CADA ELEMENTO A LAS ESCENA Y AL ARREGLO DE OBJETOS CON COLISION
					scene.add(block);
					//collisionObjects.push(block);
				});

			});

			//CARGA DE LOS CORAZONES
			loadOBJWithMTL(modelPaths.items[0].path, modelPaths.items[0].obj, modelPaths.items[0].mtl, (object) => {

				positionObject("hearts", object, mainModeHearts);

				objects.items.hearts.forEach(function (block) {
					scene.add(block);
					scene.add(block.model);
					collisionItems.push(block);
				});

			});

			//CARGA DE LOS RELOJES
			loadOBJWithMTL(modelPaths.items[1].path, modelPaths.items[1].obj, modelPaths.items[1].mtl, (object) => {

				positionObject("clocks", object, mainModeClocks);

				objects.items.clocks.forEach(function (block) {
					scene.add(block);
					scene.add(block.model);
					collisionItems.push(block);
				});

			});

			//CARGA DE LOS REGALOS
			loadOBJWithMTL(modelPaths.items[2].path, modelPaths.items[2].obj, modelPaths.items[2].mtl, (object) => {

				positionObject("gifts", object, mainModeGifts);

				objects.items.gifts.forEach(function (block) {
					scene.add(block);
					scene.add(block.model);
					collisionItems.push(block);
				});

			});

			//CARGA DE LOS PICOS
			loadOBJWithMTL(modelPaths.spikes[sceneIndex].path, modelPaths.spikes[sceneIndex].obj, modelPaths.spikes[sceneIndex].mtl, (object) => {

				positionObject("trap_spike", object, mainModeSpikes);

				objects.spikes.forEach(function (block) {
					// scene.add(block["mesh"]);
					// collisionObjects.push(block["mesh"]);
					scene.add(block);
					scene.add(block.model);
					//collisionObjects.push(block);
					collisionTrapsSpikes.push(block);
				});

			});

			// //CARGA DE LOS SEÑUELOS DE ENEMIGOS
			// loadOBJWithMTL(modelPaths.mannequins[sceneIndex].path, modelPaths.mannequins[sceneIndex].obj, modelPaths.mannequins[sceneIndex].mtl, (object) => {

			// 	object.position.z = -40;
			// 	object.position.x = -44;
			// 	if (sceneIndex == 1) {
			// 		object.position.y = 5;
			// 	}
			// 	else {
			// 		object.position.y = 0;
			// 	}

			// 	positionObject("mannequins", object, mainModeMannequins);

			// 	objects.mannequins.forEach(function (block) {
			// 		scene.add(block);
			// 		collisionObjects.push(block);
			// 	});

			// });

			// //CARGA DE LOS SEÑUELOS DE ENEMIGOS
			// loadOBJWithMTL(modelPaths.mannequins[sceneIndex].path, modelPaths.mannequins[sceneIndex].obj, modelPaths.mannequins[sceneIndex].mtl, (object) => {

			// 	object.position.z = -70;
			// 	object.position.x = -44;
			// 	if (sceneIndex == 1) {
			// 		object.position.y = 5;
			// 	}
			// 	else {
			// 		object.position.y = 0;
			// 	}
			// 	positionObject("enemies", object, mainModeEnemies);
			// 	objects.enemies.forEach(function (block) {
			// 		scene.add(block);
			// 		collisionObjects.push(block);
			// 	});

			// });

			//CARGA DE ENEMIGOS Y SEÑUELOS
			loadOBJWithMTL(modelPaths.mannequins[sceneIndex].path, modelPaths.mannequins[sceneIndex].obj, modelPaths.mannequins[sceneIndex].mtl, (object) => {


				positionObject("mannequins", object, mainModeMannequins);
				rotateObjects(objects.mannequins, mainModeWalls);

				positionObject("enemies", object, mainModeEnemies);
				rotateObjects(objects.enemies, mainModeWalls);

				objects.mannequins.forEach(function (block) {
					scene.add(block.model);
				});

				objects.enemies.forEach(function (block) {
					scene.add(block.model);
					collisionEnemies.push(block);
				});

			});

			//CARGA DE LAS DECORACIONES
			loadOBJWithMTL(modelPaths.decorators[sceneIndex].path, modelPaths.decorators[sceneIndex].obj, modelPaths.decorators[sceneIndex].mtl, (object) => {

				object.position.z = -40;
				object.position.x = -44;
				object.position.y = 0;

				positionObject("decorators", object, mainModeDecorators);

				rotateObjects(objects.decorators, mainModeWalls);

				objects.decorators.forEach(function (block) {
					scene.add(block);
					scene.add(block.model);
					collisionObjects.push(block);
				});

				isWorldReady[0] = true;

			});


			document.addEventListener('keydown', onKeyDown);
			document.addEventListener('keyup', onKeyUp);

			var loader = new THREE.FBXLoader();

			//Carga del personaje principal
			loader.load('assets/Animacion.fbx', function (personaje) {
				personaje.mixer = new THREE.AnimationMixer(personaje);
				mixersP.push(personaje.mixer);
				var action =
					personaje.mixer.clipAction(personaje.animations[0]);
				action.play();


				personaje.position.z = -40; //SE POSICIONA EL ASSET EN EL ORIGEN
				personaje.position.x = -44;

				personaje.coordZ = mainModePlayer[0][0];
				personaje.coordX = mainModePlayer[0][1];
				personaje.position.x += personaje.coordX * 10;
				personaje.position.z += personaje.coordZ * 10;

				personaje.name = "player1";
				personaje.lives = 3;
				personaje.alive = true;
				personaje.score = 3000;
				rayCaster.push(new THREE.Raycaster());
				timer.push(new THREE.Clock(true));

				scene.add(personaje);
				players.push(personaje);

				isWorldReady[1] = true;

			});

			//Carga del otro jugador (apartado)
			loader.load('assets/Animacion2.fbx', function (personaje) {
				personaje.mixer = new THREE.AnimationMixer(personaje);
				mixersP.push(personaje.mixer);
				var action =
					personaje.mixer.clipAction(personaje.animations[0]);
				action.play();


				personaje.position.z = -40; //SE POSICIONA EL ASSET EN EL ORIGEN
				personaje.position.x = -44;

				personaje.coordZ = mainModePlayer[0][0];
				personaje.coordX = mainModePlayer[0][1];
				personaje.position.x += personaje.coordX * 10;
				personaje.position.z += personaje.coordZ * 10;

				personaje.name = "player2";
				personaje.lives = 3;
				personaje.alive = true;
				personaje.score = 3000;
				rayCaster.push(new THREE.Raycaster());
				timer.push(new THREE.Clock(true));

				scene.add(personaje);
				players.push(personaje);

				for (let index = 0; index < players.length; index++) {
					var top = (index + 1) * 10;
					var esteticId = index + 1;
					$("body").append("<div class='nameplay' id='" + "playNombre" + index + "'style='position: absolute; top:" + top + "%; left:80%; color:beige;'><label for='playerName' id='" + "playerName" + index + "'>" + "Jugador: " + esteticId + "</label><br><label id='" + "playerScore" + index + "' for=''></label><br><label id='" + "playerLives" + index + "' for=''></label></div>")
					var id = "#playName" + index;
					$(id).show();
				}

				isWorldReady[2] = true;

			});

			render();
		});

		function loadOBJWithMTL(path, objFile, mtlFile, onLoadCallback) {
			var mtlLoader = new THREE.MTLLoader();
			mtlLoader.setPath(path);
			mtlLoader.load(mtlFile, (materials) => {

				var objLoader = new THREE.OBJLoader();
				objLoader.setMaterials(materials);
				objLoader.setPath(path);
				objLoader.load(objFile, (object) => {
					onLoadCallback(object);
				});

			});
		}

		function onKeyDown(event) {
			keys[String.fromCharCode(event.keyCode)] = true;
		}

		function onKeyUp(event) {
			keys[String.fromCharCode(event.keyCode)] = false;
		}

		function render() {
			requestAnimationFrame(render);

			deltaTime = clock.getDelta();
			deltaTime2 = deltaTime * enemySpeed;

			var yaw = [0, 0];
			var forward = [0, 0];
			var moving = [false, false];

			if (players[0] != null && players[0].alive) {
				if (keys["A"]) {
					yaw[0] = 5;
					moving[0] = true;
				} else if (keys["D"]) {
					yaw[0] = -5;
					moving[0] = true;
				}
				if (keys["W"]) {
					forward[0] = 20;
					moving[0] = true;
				} else if (keys["S"]) {
					forward[0] = -20;
					moving[0] = true;
				}
			}

			if (players[1] != null && players[1].alive) {
				if (keys["J"]) {
					yaw[1] = 5;
					moving[1] = true;
				} else if (keys["L"]) {
					yaw[1] = -5;
					moving[1] = true;
				}
				if (keys["I"]) {
					forward[1] = 20;
					moving[1] = true;
				} else if (keys["K"]) {
					forward[1] = -20;
					moving[1] = true;
				}
			}


			if (estaPausado == false) {

				////ANIMACIONES
				for (let index = 0; index < mixersP.length; index++) {
					if (moving[index]) {
						mixersP[index].update(deltaTime);
					}

				}


				//// LOGICA DE COLISIONES
				if (isWorldReady[0] && isWorldReady[1] && isWorldReady[2]) {

					for (let index = 0; index < players.length; index++) {

						$("#playerScore" + index).html("Puntuacion: " + players[index].score);
						$("#playerLives" + index).html("Vidas restantes: " + players[index].lives);

						players[index].rotation.y += yaw[index] * deltaTime;
						players[index].translateZ(forward[index] * deltaTime);

						if (players[index].lives <= 0) {
							players[index].alive = false;
							playerAlive = false;

							if (players[index].scale.x > 0) {
								players[index].scale.x -= 1 * deltaTime;
								players[index].scale.y -= 1 * deltaTime;
								players[index].scale.z -= 1 * deltaTime;
								players[index].rotation.y += THREE.Math.degToRad(150) * deltaTime;
							}
							else {
								scene.remove(players[index]);
							}

							for (let j = 0; j < players.length; j++) {
								const element = players[j];
								if (element.alive) {
									playerAlive = true;
									break;
								}
							}

							if (!playerAlive) {
								estaPausado = true;
								$("#DeathScreen").show();
							}
						}
					}

					// Collision
					// Recorremos los "rayos" a lanzar para detectar las colisiones
					for (var i = 0; i < camera.rays.length; i++) {

						for (let index = 0; index < rayCaster.length; index++) {
							rayCaster[index].set(players[index].position, camera.rays[i]);
						}

						for (let index = 0; index < players.length; index++) {

							var esteticId = index + 1;
							var collision = rayCaster[index].intersectObjects(collisionObjects, true);
							var collisionE = rayCaster[index].intersectObjects(collisionEnemies, true);
							var collisionTS = rayCaster[index].intersectObjects(collisionTrapsSpikes, true);
							var collisionTW = rayCaster[index].intersectObjects(collisionTrapsWalls, true);
							var collisionW = rayCaster[index].intersectObjects(winCollision, true);
							var collisionIt = rayCaster[index].intersectObjects(collisionItems, true);


							if (collision.length > 0 && collision[0].distance < 1) {
								console.log("colisionando jugador " + esteticId);
								players[index].translateZ(-(forward[index] * deltaTime));

							}


							/////////////////////////////////
							/////COLISIONES CON PICOS://///
							/////////////////////////////////
							objects.spikes.forEach(function (spike) {
								if (spike.activated && spike.model.position.y < 0) {
									spike.model.position.y += 10 * deltaTime;
								}
							})

							if (collisionTS.length > 0 && collisionTS[0].distance < 1) {
								if (!collisionTS[0].object.activated)
									collisionTS[0].object.activated = true;


								console.log("Picos activados por el jugador " + esteticId);
								players[index].translateZ(-(forward[index] * 0.90 * deltaTime));

								if (!timer[index].running);
								timer[index].start();

							}

							////////////////////////////////////////////
							/////COLISIONES CON PAREDES APLASTANTES:////
							////////////////////////////////////////////
							objects.crushWalls.forEach(function (crushWall) {
								if (crushWall.activated) {


									if (crushWall.wall_1.position.z < crushWall.position.z - 0.005)
										crushWall.wall_1.position.z += 10 * deltaTime;
									else if (crushWall.wall_1.position.z > crushWall.position.z + 0.005)
										crushWall.wall_1.position.z -= 10 * deltaTime;

									if (crushWall.wall_2.position.z < crushWall.position.z - 0.005)
										crushWall.wall_2.position.z += 10 * deltaTime;
									else if (crushWall.wall_2.position.z > crushWall.position.z + 0.005)
										crushWall.wall_2.position.z -= 10 * deltaTime;

									if (crushWall.wall_1.position.x < crushWall.position.x - 0.005)
										crushWall.wall_1.position.x += 10 * deltaTime;
									else if (crushWall.wall_1.position.x > crushWall.position.x + 0.005)
										crushWall.wall_1.position.x -= 10 * deltaTime;

									if (crushWall.wall_2.position.x < crushWall.position.x - 0.005)
										crushWall.wall_2.position.x += 10 * deltaTime;
									else if (crushWall.wall_2.position.x > crushWall.position.x + 0.005)
										crushWall.wall_2.position.x -= 10 * deltaTime;

								}
							})

							if (collisionTW.length > 0 && collisionTW[0].distance < 1) {
								if (!collisionTW[0].object.activated)
									collisionTW[0].object.activated = true;


								console.log("Paredes activadas por el jugador " + esteticId);
								players[index].translateZ(-(forward[index] * 0.90 * deltaTime));

								if (!timer[index].running)
									timer[index].start();

							}

							////////////////////////////////////////////
							/////COLISIONES CON ENEMIGOS////////////////
							////////////////////////////////////////////
							if (collisionE.length > 0 && collisionE[0].distance < 1) {

								console.log("Colision con enemigo");
								players[index].translateZ(-(forward[index] * 0.95 * deltaTime));

								if (!timer[index].running)
									timer[index].start();

							}

							//////////////////////////////////////////////////////
							/////DELAY ANTES DE REGRESAR AL JUGADOR AL ORIGEN/////
							//////////////////////////////////////////////////////
							if (timer[index].running) {
								if (timer[index].getElapsedTime() > 0.6) {
									players[index].lives -= 1;

									players[index].position.z = -40 + players[index].coordZ * 10; //SE POSICIONA EL ASSET
									players[index].position.x = -44 + players[index].coordX * 10;
									players[index].rotation.y = 0.0;

									players[index].score -= 1000;
									console.log("Vidas: " + players[index].lives);

									timer[index].stop();
								}
							}

							/////////////////////////////////
							/////COLISIONES CON ITEMS/////
							/////////////////////////////////

							Object.keys(objects.items).forEach(function (key) {
								objects.items[key].forEach(function (item) {
									if (item.taken && item.model.scale.x > 0) {
										item.model.scale.x -= 0.5 * deltaTime;
										item.model.scale.y -= 0.5 * deltaTime;
										item.model.scale.z -= 0.5 * deltaTime;
										item.model.rotation.y += THREE.Math.degToRad(150) * deltaTime;
									}
									else if (item.taken) {
										scene.remove(item.model);
										scene.remove(item);
									}
								})
							})


							if (collisionIt.length > 0 && collisionIt[0].distance < 1) {
								if (!collisionIt[0].object.taken) {
									collisionIt[0].object.taken = true;

									if (collisionIt[0].object.itemType == "heart") {
										console.log("Vida extra para jugador " + esteticId);
										players[index].lives += 1;
										players[index].score += 1000;
									}

									if (collisionIt[0].object.itemType == "gift") {
										console.log("Vida perdida del jugador " + esteticId);
										players[index].lives -= 1;
										players[index].score -= 1000;
									}

									if (collisionIt[0].object.itemType == "clock") {
										console.log("Enemigos ralentizados por el jugador " + esteticId);
										slowerEnemies = true;
										players[index].score += 500;
										timer2.start();
									}
								}
							}

							if (slowerEnemies) {
								if (timer2.getElapsedTime() < 8)
									enemySpeed = 0.5;
								else {
									slowerEnemies = false;
									enemySpeed = 1;
									timer2.stop();
								}
							}

							/////////////////////////////////////////////
							/////COLISIONES CON SALIDA DEL LABERINTO:////
							/////////////////////////////////////////////
							if (collisionW.length > 0 && collisionW[0].distance < 1) {

								var winTimeS = Math.round(clock.getElapsedTime());
								var finalScore = players[index].score + 10000 - (winTimeS * 50);
								var winTimeD = new Date(winTimeS * 1000).toISOString().substr(14, 5)
								var esteticId = index + 1;

								$("#playerResults").html("Jugador:" + esteticId + " Tiempo: " + winTimeD + " Puntuacion final: " + finalScore);
								$("#puntos").val(finalScore);
                                estaPausado = true;
								$("#VictoryScreen").show();
							}

						}

					}

					Object.keys(objects.items).forEach(function (key) {
						objects.items[key].forEach(function (item) {
							item.model.rotation.y += THREE.Math.degToRad(50) * deltaTime;
						});
					});

					objects.mannequins.forEach(function (mannequin) {
						mannequin.model.translateX(((Math.sin(clock.getElapsedTime()) + 1) / 2 * distancia + posInicial) * deltaTime2);					
					});

					objects.enemies.forEach(function (enemy) {
						enemy.model.translateX(((Math.sin(clock.getElapsedTime()) + 1) / 2 * distancia + posInicial) * deltaTime2);
					});

					renderer.render(scene, camera);
				}


			}
		}

		function setupScene() {
			var visibleSize = { width: window.innerWidth, height: window.innerHeight };
			clock = new THREE.Clock();
			scene = new THREE.Scene();

			//camera = new THREE.PerspectiveCamera(100, visibleSize.width / visibleSize.height, 0.1, 120);

			var aspect = window.innerWidth / window.innerHeight;
			var d = 90;
			camera = new THREE.OrthographicCamera(- d * aspect, d * aspect, d, - d, 1, 1000);
			camera.position.set(50, 60, 85); // all components equal
			//camera.lookAt(0, 0, 0); // or the origin

			//camera.rotation.y = THREE.Math.degToRad(-5);
			camera.rotation.x = THREE.Math.degToRad(-60);

			camera.rays = [
				new THREE.Vector3(1, 0, 0),
				new THREE.Vector3(-1, 0, 0),
				new THREE.Vector3(0, 0, 1),
				new THREE.Vector3(0, 0, -1),
			];

			renderer = new THREE.WebGLRenderer({ precision: "mediump", alpha:true });
            renderer.setClearColor(new THREE.Color(1, 0, 0), 0);
			renderer.setPixelRatio(visibleSize.width / visibleSize.height);
			renderer.setSize(visibleSize.width, visibleSize.height);

			var ambientLight = new THREE.AmbientLight(new THREE.Color(1, 1, 1), 1.0);
			scene.add(ambientLight);


			var directionalLight = new THREE.DirectionalLight(new THREE.Color(1, 1, 0), 0.4);
			directionalLight.position.set(0, 0, 1);
			scene.add(directionalLight);

			var grid = new THREE.GridHelper(200, 50, 0xffffff, 0xffffff);
			grid.position.y = -1;
			//scene.add(grid);

			$("#scene-section").append(renderer.domElement);
		}


	</script>
</head>

<body>

	<div id="scene-section"></div>
    <audio autoplay loop>
		<source src="../audio/Rain.mp3" type="audio/mp3">
		Tu navegador no soporta HTML5 audio.
  	</audio>
	  <div class="modal-dialog" id="VictoryScreen" style="position: absolute; top:20%; left:40%;">
		<div class="modal-content">
			
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">¡GANASTE!</h5>
				<br>
				<h5 class="modal-title" id="playerResults"> Hola</h5>
			</div>
			<div class="modal-body">
				<br>
				<button type="button" class="btn btn-dark border border-warning">
					<a class="btnSave" style=" top:20%; right:90%;" href="../index.html">Volver al menú principal</a>
				</button>

                <form id="saveDataUser" style="right:50%;" method="post">
				<input type="text" placeholder="Nombre" id="username" name="name">
                <input id="puntos" name="puntos" hidden="true">
				<button id="" class="btn btn-dark border border-warning" type="submit">Guardar</button>
                </form>

                <?php
                
                include("php/guardardatos.php");
                
                ?> 
				
				<br><br>
				<button class="btn btn-dark border border-warning" onclick="shareFB()">Compartir en Facebook</button>
				<br>
				
				<br>
				<button type="button" class="btn btn-dark border border-warning">
					<a class="btnSave" style=" top:20%; right:60%;" href="../index.html">Volver al menú principal</a>
				</button>
				<br><br>
				<button type="button" class="btn btn-dark border border-warning">
					<a class="btnSave" href="indexMultiMediev.php">Volver a jugar</a>
				</button>
				<br>
				<br>
				<button type="button" class="btn btn-dark border border-warning">
					<a class="btnSave" href="../elegirEscenario.html">Seleccionar otro nivel</a>
				</button>
			</div>

		</div>
	</div>

	<div class="modal-dialog" id="DeathScreen" style="position: absolute; top:20%; left:40%;">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">¡HAN PERDIDO!</h5>

			</div>
			<div class="modal-body">

				<br>
				<button type="button" class="btn btn-dark border border-warning">
					<a class="btnSave" style=" top:20%; right:90%;" href="../index.html">Volver al menú principal</a>
				</button>
				<br>
				<br>
				<button type="button" class="btn btn-dark border border-warning">
					<a class="btnSave" href="indexMultiMediev.php">Volver a intentar</a>
				</button>
				<br>
				<br>
				<button type="button" class="btn btn-dark border border-warning">
					<a class="btnSave" href="../elegirEsceMulti.html">Seleccionar otro nivel</a>
				</button>
			</div>

		</div>
	</div>

</body>

</html>