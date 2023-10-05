<?php

header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;



return function (App $app) {
    $container = $app->getContainer();


	$app->group('/api/rest', function($app) {

		/* ####################### START v1 ####################### */
		$app->group('/v1', function($app) {

			/* ####################### Gestione Info Borghi ####################### */

			/* Definisco l'API REST per la restituzione dell'elenco dei borghi */
			$path = '/borghi';
			$app->get($path, function ($request, $response, $args) {

				$BorghiManagement = new BorghiManagementImpl();
				$result = $BorghiManagement->getList();

				return $this->response->withJson($result['body'],$result['status_code']);

			});


			/* Definisco l'API REST per la restituzione delle info di un borgo */
			$path = '/borghi/{id:[0-9][0-9]*}';
			$app->get($path, function ($request, $response, $args) {

				$BorghiManagement = new BorghiManagementImpl();
				$result = $BorghiManagement->getInfoBorgo($args['id']);

				return $this->response->withJson($result['body'],$result['status_code']);

			});


			/* ####################### Gestione Palii ####################### */

			/* Definisco l'API REST per la restituzione dell'elenco dei borghi */
			$path = '/borghi/{id:[0-9][0-9]*}/palii';
			$app->get($path, function ($request, $response, $args) {

				$PaliiManagement = new PaliiManagementImpl();
				$result = $PaliiManagement->getList($args['id']);

				return $this->response->withJson($result['body'],$result['status_code']);

			});


			/* Definisco l'API REST per la restituzione delle info di un borgo */
			$path = '/palii/{id:[0-9][0-9]*}';
			$app->get($path, function ($request, $response, $args) {

				$PaliiManagement = new PaliiManagementImpl();
				$result = $PaliiManagement->getInfoPalio($args['id']);

				return $this->response->withJson($result['body'],$result['status_code']);

			});


			/* ####################### Gestione Lista Eventi ####################### */

			/* Definisco l'API REST per la restituzione dell'elenco dei borghi */
			$path = '/events';
			$app->get($path, function ($request, $response, $args) {

				$EventsManagement = new EventsManagementsImpl();
				$result = $EventsManagement->getList();

				return $this->response->withJson($result['body'],$result['status_code']);

			});

			/* Definisco l'API REST per la restituzione dell'elenco dei borghi */
			$path = '/events/{id}';
			$app->get($path, function ($request, $response, $args) {

				$EventsManagement = new EventsManagementsImpl();
				$result = $EventsManagement->getEvent($args['id']);

				return $this->response->withJson($result['body'],$result['status_code']);

			});

			/* Definisco l'API REST per ottenere gli eventi di un borgo*/
			$path = '/borghi/{id}/events';
			$app->get($path, function ($request, $response, $args) {

				$EventsManagements = new EventsManagementsImpl();
				$result = $EventsManagements->getListByBorgo($args['id']);

				return $this->response->withJson($result['body'],$result['status_code']);

			});	



		    /* ####################### Gestione Utente ####################### */

			/* Definisco l'API REST per la registrazione al sistema */
			$path = '/register';
			$app->post($path, function ($request, $response, $args) {

				$UserManagement = new UsersManagementImpl();
				$post = $request->getParams();
				$result = $UserManagement->register($post['name'],
													$post['surname'],
													$post['email'],
													$post['password']);

				return $this->response->withJson($result['body'],$result['status_code']);

			});



			$app->post('/login', function (Request $request, Response $response, array $args) {
			 
				$UserManagement = new UsersManagementImpl();
			    $settings = $this->get('settings'); // get settings array.
			    $post = $request->getParams();

				$result = $UserManagement->login($post['email'],$post['password']);


				if($result['status_code'] == 200){
					$token = JWT::encode(['id' => $result['body']['response']['id'], 
										 'email' => $result['body']['response']['email']], 
										 $settings['jwt']['secret'], "HS256");
					$result['body']['response']['token'] = $token;
				}else{
					$result['body']['response']['token'] = null;
				}
			
			    return $this->response->withJson($result['body'],$result['status_code']);
			 
			});


			/* ####################### Gestione Delle Pagine Private ####################### */
			/* ############################## START AUTH ################################### */
			$app->group('/auth', function($app) {

				/* ############## GESTIONE EVENTI #################### */

				/* Definisco l'API REST per l'inserimento di un evento */
				$path = '/borghi/{id}/events';
				$app->post($path, function ($request, $response, $args) {

					$EventsManagements = new EventsManagementsImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];
					$post = $request->getParams();

					$result = $EventsManagements->addEvent($UserId,
														   $args['id'],
														   $post['date'],
														   $post['name'],
														   $post['description'],
														   $post['places'],
														   $post['state']);

					return $this->response->withJson($result['body'],$result['status_code']);

				});	


				/* Definisco l'API REST per la modifica di un evento */
				$path = '/borghi/{borgoId}/events/{eventId}';
				$app->put($path, function ($request, $response, $args) {

					$EventsManagements = new EventsManagementsImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];
					$put = $request->getParams();

					$result = $EventsManagements->updateEvent($UserId,
															  $args['borgoId'],
														      $args['eventId'],
														      $put['date'],
														      $put['name'],
														      $put['description'],
														      $put['places'],
														      $put['state']);

					return $this->response->withJson($result['body'],$result['status_code']);

				});	


				/* Definisco l'API REST per la cancellazione di un evento */
				$path = '/borghi/{borgoId}/events/{eventId}';
				$app->delete($path, function ($request, $response, $args) {

					$EventsManagements = new EventsManagementsImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];

					$result = $EventsManagements->removeEvent($UserId,$args['borgoId'],$args['eventId']);

					return $this->response->withJson($result['body'],$result['status_code']);

				});	



				/* ############## GESTIONE ACCOUNT #################### */


				/* Definisco l'API REST per l'aggiornamento delle informazioni utente */
				$path = '/users/info';
				$app->patch($path, function ($request, $response, $args) {

					$UserManagement = new UsersManagementImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];
					$put = $request->getParams();

					$result = $UserManagement->updateUserInfo($put['name'],$put['surname'],$put['email'],$UserId);

					return $this->response->withJson($result['body'],$result['status_code']);

				});


				/* Definisco l'API REST per la modifica della password*/
				$path = '/users/password';
				$app->patch($path, function ($request, $response, $args) {

					$UserManagement = new UsersManagementImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];
					$put = $request->getParams();

					$result = $UserManagement->updatePassword($put['password'],$put['oldpassword'],$UserId);

					return $this->response->withJson($result['body'],$result['status_code']);

				});


				/* Definisco l'API REST per la rimozione di un account */
				$path = '/users/remove';
				$app->delete($path, function ($request, $response, $args) {

					$UserManagement = new UsersManagementImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];


					$result = $UserManagement->removeAccount($UserId);

					return $this->response->withJson($result['body'],$result['status_code']);

				});


				/* ############## GESTIONE ISCRIZIONE BORGHI #################### */


				/* Definisco l'API REST per la restituzione ad un borgo */
				$path = '/borghi/{borgoId}/users/inscription';
				$app->post($path, function ($request, $response, $args) {

					$InscriptionsManagement = new InscriptionsManagementImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];

					$result = $InscriptionsManagement->inscription($args['borgoId'],$UserId);

					return $this->response->withJson($result['body'],$result['status_code']);

				});

				/* Definisco l'API REST per la cancellazione ad un borgo */
				$path = '/borghi/users/inscription';
				$app->delete($path, function ($request, $response, $args) {

					$InscriptionsManagement = new InscriptionsManagementImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];

					$result = $InscriptionsManagement->delete($UserId);

					return $this->response->withJson($result['body'],$result['status_code']);

				});



				/* Definisco l'API REST per l'attivazione dell'iscrizione al borgo */
				$path = '/roles';
				$app->get($path, function ($request, $response, $args) {

					$RolesManagement = new RolesManagementImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];

					$result = $RolesManagement->getList($UserId);

					return $this->response->withJson($result['body'],$result['status_code']);

				});


				/* Definisco l'API REST per la restituzione degli iscritti ad un borgo */
				$path = '/borghi/{borgoId}/users';
				$app->get($path, function ($request, $response, $args) {

					$UserManagement = new UsersManagementImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];

					$result = $UserManagement->listUsersBorgo($args['borgoId'],$UserId);

					return $this->response->withJson($result['body'],$result['status_code']);

				});	


				/* Definisco l'API REST per l'attivazione dell'iscrizione al borgo */
				$path = '/borghi/{borgoid}/users/{userId}/active';
				$app->patch($path, function ($request, $response, $args) {

					$InscriptionsManagement = new InscriptionsManagementImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];

					$result = $InscriptionsManagement->active($args['borgoid'],$args['userId'],$UserId);

					return $this->response->withJson($result['body'],$result['status_code']);

				});	


				/* Definisco l'API REST per l'attivazione dell'iscrizione al borgo */
				$path = '/borghi/{borgoId}/users/{userId}/deactivates';
				$app->patch($path, function ($request, $response, $args) {

					$InscriptionsManagement = new InscriptionsManagementImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];

					$result = $InscriptionsManagement->deactivates($args['borgoId'],
																   $args['userId'],
																   $UserId);

					return $this->response->withJson($result['body'],$result['status_code']);

				});


				/* Definisco l'API REST per l'attivazione dell'iscrizione al borgo */
				$path = '/borghi/{borgoId}/users/{userId}/roles/{roleId}';
				$app->patch($path, function ($request, $response, $args) {

					$InscriptionsManagement = new InscriptionsManagementImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];

					$result = $InscriptionsManagement->changePermissions($args['borgoId'],
																	     $args['userId'],
																	     $args['roleId'],
																	     $UserId);

					return $this->response->withJson($result['body'],$result['status_code']);

				});


				/* Definisco l'API REST per l'attivazione dell'iscrizione al borgo */
				$path = '/borghi/{borgoId}/users/inscription/events/{eventId}';
				$app->post($path, function ($request, $response, $args) {

					$ReservationsManagement = new ReservationsManagementImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];
					$post = $request->getParams();

					$result = $ReservationsManagement->signUpForTheEvent($args['borgoId'],
																	     $args['eventId'],
																	     $post['places'],
																	     $UserId);

					return $this->response->withJson($result['body'],$result['status_code']);

				});



				/* Definisco l'API REST per il recupero di tutte le iscrizioni a gli eventi */
				$path = '/users/inscription/events';
				$app->get($path, function ($request, $response, $args) {

					$ReservationsManagement = new ReservationsManagementImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];

					$result = $ReservationsManagement->getListReservations($UserId);

					return $this->response->withJson($result['body'],$result['status_code']);

				});


				/* Definisco l'API REST per il recupero di tutte le iscrizioni a gli eventi */
				$path = '/borghi/{borgoId}/events/{eventId}/users';
				$app->get($path, function ($request, $response, $args) {

					$ReservationsManagement = new ReservationsManagementImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];

					$result = $ReservationsManagement->getListReservationsEvent($args['borgoId'],
																			    $args['eventId'],
																			    $UserId);

					return $this->response->withJson($result['body'],$result['status_code']);

				});


				/* Definisco l'API REST per il recupero di tutte le iscrizioni a gli eventi */
				$path = '/borghi/{borgoId}/events/{eventId}/users/{userId}/accept';
				$app->patch($path, function ($request, $response, $args) {

					$ReservationsManagement = new ReservationsManagementImpl();
					$UserId = $request->getAttribute('decoded_token_data')['id'];

					$result = $ReservationsManagement->confirmationOfReservation($args['borgoId'],
																			     $args['eventId'],
																			     $args['userId'],
																			     $UserId);

					return $this->response->withJson($result['body'],$result['status_code']);

				});		


			});
			/* ################################### END AUTH ################################ */

		});
		/* ####################### END v1 ####################### */

	   
	});




};
