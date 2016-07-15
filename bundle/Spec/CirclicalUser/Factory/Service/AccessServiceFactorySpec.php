<?php

namespace Spec\CirclicalUser\Factory\Service;

use CirclicalUser\Provider\GroupActionRuleProviderInterface;
use CirclicalUser\Provider\UserActionRuleProviderInterface;
use CirclicalUser\Provider\UserInterface as User;
use CirclicalUser\Mapper\RoleMapper;
use CirclicalUser\Service\AccessService;
use CirclicalUser\Service\AuthenticationService;
use PhpSpec\ObjectBehavior;
use Zend\ServiceManager\ServiceManager;

class AccessServiceFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CirclicalUser\Factory\Service\AccessServiceFactory');
    }

    function it_creates_its_service(ServiceManager $serviceManager, RoleMapper $roleMapper, GroupActionRuleProviderInterface $ruleMapper, UserActionRuleProviderInterface $userActionRuleMapper, AuthenticationService $authenticationService)
    {
        $config = [

            'circlical' => [
                'user' => [
                    'providers' => [
                        'role' => RoleMapper::class,
                        'rule' => [
                            'group' => GroupActionRuleProviderInterface::class,
                            'user' => UserActionRuleProviderInterface::class,
                        ],
                    ],
                    'auth' => [
                        'crypto_key' => 'sfZGFm1rCc7TgPr9aly3WOtAfbEOb/VafB8L3velkd0=',
                        'transient' => false,
                    ],
                    'guards' => [
                        'Foo' => [
                            // controller-level-permissions
                            'controllers' => [
                                'Foo\Controller\ThisController' => [
                                    'default' => ['user'],
                                    'actions' => [
                                        'index' => ['user'],
                                        'userList' => ['admin'],
                                    ],
                                ],
                                'Foo\Controller\AdminController' => [
                                    'default' => ['admin'],
                                    'actions' => [
                                        'oddity' => ['user'],
                                        'superodd' => [],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $serviceManager->get('config')->willReturn($config);
        $serviceManager->get(RoleMapper::class)->willReturn($roleMapper);
        $serviceManager->get(GroupActionRuleProviderInterface::class)->willReturn($ruleMapper);
        $serviceManager->get(UserActionRuleProviderInterface::class)->willReturn($userActionRuleMapper);
        $serviceManager->get(AuthenticationService::class)->willReturn($authenticationService);
        $this->createService($serviceManager)->shouldBeAnInstanceOf(AccessService::class);
    }

    function it_creates_its_service_with_user_identity(ServiceManager $serviceManager, RoleMapper $roleMapper, GroupActionRuleProviderInterface $ruleMapper, UserActionRuleProviderInterface $userActionRuleMapper, AuthenticationService $authenticationService, User $user)
    {
        $config = [

            'circlical' => [
                'user' => [
                    'providers' => [
                        'role' => RoleMapper::class,
                        'rule' => [
                            'group' => GroupActionRuleProviderInterface::class,
                            'user' => UserActionRuleProviderInterface::class,
                        ],
                    ],
                    'guards' => [
                        'Foo' => [
                            // controller-level-permissions
                            'controllers' => [
                                'Foo\Controller\ThisController' => [
                                    'default' => ['user'],
                                    'actions' => [
                                        'index' => ['user'],
                                        'userList' => ['admin'],
                                    ],
                                ],
                                'Foo\Controller\AdminController' => [
                                    'default' => ['admin'],
                                    'actions' => [
                                        'oddity' => ['user'],
                                        'superodd' => [],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $user->getId()->willReturn(1);
        $authenticationService->getIdentity()->willReturn($user);

        $serviceManager->get('config')->willReturn($config);
        $serviceManager->get(RoleMapper::class)->willReturn($roleMapper);
        $serviceManager->get(GroupActionRuleProviderInterface::class)->willReturn($ruleMapper);
        $serviceManager->get(UserActionRuleProviderInterface::class)->willReturn($userActionRuleMapper);
        $serviceManager->get(AuthenticationService::class)->willReturn($authenticationService);
        $this->createService($serviceManager)->shouldBeAnInstanceOf(AccessService::class);
    }
}
