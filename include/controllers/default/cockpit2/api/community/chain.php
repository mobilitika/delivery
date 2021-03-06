<?php

class Controller_api_community_chain extends Crunchbutton_Controller_RestAccount {

	public function init() {

		if (!c::admin()->permission()->check(['global', 'community-all', 'chain-all', 'chain-view', 'chain-crud','community-director'])) {
			$this->error(401, true);
		}

		switch ( $this->method() ) {

			case 'get':
				switch ( c::getPagePiece( 3 ) ) {

					case 'by-community':
						if( c::getPagePiece( 4 ) ){
							$out = [];
							$chains = Cockpit_Community_Chain::byCommunity( c::getPagePiece( 4 ) );
							foreach( $chains as $chain ){
								$_chain = [];
								$_chain[ 'id_community_chain' ] = $chain->id_community_chain;
								$_chain[ 'name' ] = $chain->name;
								$restaurant = Cockpit_Restaurant_Chain::byIdCommunityChain( $chain->id_community_chain );
								if( count( $restaurant ) ){
									$restaurant = $restaurant->get( 0 );
									if( $restaurant ){
										$restaurant = $restaurant->restaurant();
										if( $restaurant->id_restaurant ){
											$_chain[ 'name' ] .= ' - linked to: ' . $restaurant->name;
										}
									}
								}
								$out[] = $_chain;
							}
							echo json_encode( $out );exit;
						} else {
							$this->error(404, true);
						}
					break;

					default:

						$chain = Community_Chain::o( c::getPagePiece( 3 ) );
						if (!$chain->id_chain) {
							$this->error(404, true);
						}
						echo $chain->json();exit();
				}

				break;

			case 'post':
				if (!c::admin()->permission()->check(['global','chain-all', 'chain-crud'])) {
					$this->error(401, true);
				}

				switch ( c::getPagePiece(3) ) {
					default:

						$id_community_chain = $this->request()[ 'id_community_chain' ];
						if( $id_community_chain ){
							$communityChain = Community_Chain::o( $id_community_chain );
						} else {
							$communityChain = Community_Chain::byCommunityChan( $this->request()[ 'id_community' ], $this->request()[ 'id_chain' ] );
							if( !$communityChain->id_community_chain ){
								$communityChain = new Community_Chain;
							}
						}
						$communityChain->id_chain = $this->request()[ 'id_chain' ];
						$communityChain->id_community = $this->request()[ 'id_community' ];
						$communityChain->exist_at_community = $this->request()[ 'exist_at_community' ];
						$communityChain->within_range = $this->request()[ 'within_range' ];

						$communityChain->save();

						Cockpit_Restaurant_Chain::removeChainsByIdCommunityChain( $communityChain->id_community_chain );

						if( $this->request()[ 'linked_restaurant' ] && $this->request()[ 'id_restaurant' ] ){
							$restaurantChain = new Restaurant_Chain;
							$restaurantChain->id_community_chain = $communityChain->id_community_chain;
							$restaurantChain->id_restaurant = $this->request()[ 'id_restaurant' ];
							$restaurantChain->save();
						}

						if( $communityChain->id_chain ){
							echo $communityChain->json();
						} else {
							$this->_error( 'error' );
						}
						break;
				}
				break;
		}
	}

	private function _error( $error = 'invalid request' ){
		echo json_encode( [ 'error' => $error ] );
		exit();
	}
}