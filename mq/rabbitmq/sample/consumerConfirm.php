<?php 
require __DIR__ . '/../../../vendor/autoload.php';

/*
 *
消息中间件的作用

解耦：在项目启动之初来预测将来项目会碰到什么需求，是极其困难的。消息队列在处理过程中间插入了一个隐含的、
基于数据的接口层，两边的处理过程都要实现这一接口。这允许你独立的扩展或修改两边的处理过程，只要确保它们遵守同样的接口约束。

冗余（存储）：有时在处理数据的时候处理过程会失败。除非数据被持久化，否则将永远丢失。消息队列把数据进行持久化直到它们已经被完全处理，
通过这一方式规避了数据丢失风险。在被许多消息队列所采用的"插入-获取-删除"范式中，在把一个消息从队列中删除之前，需要你的处理过程明确的指出该消息已经被处理完毕，
确保你的数据被安全的保存直到你使用完毕。

扩展性：因为消息中间件解捐了应用的处理过程，所以提高消息入队和处理的效率是很容 易的，只要另外增加处理过程即可，不需要改变代码，也不需要调节参数。

流量削峰: 在访问量剧增的情况下，应用仍然需要继续发挥作用，但是这样的突发流 量 并不常 见。如果以能处理这类峰值为标准而投入资源，
无疑是巨大的浪费 。 使用消息中间件能够使关 键组件支撑突发访问压力，不会因为突发的超负荷请求而完全崩惯 。
可恢复性： 当系统一部分组件失效时，不会影响到整个系统 。 消息中间件降低了进程间的 稿合度，所以即使一个处理消息的进程挂掉，
加入消息中间件中的消息仍然可以在系统恢复后 进行处理 。
顺序保证: 在大多数使用场景下，数据处理的顺序很重要，大部分消息中间件支持一定程 度上的顺序性。

缓冲: 在任何重要的系统中，都会存在需要不同处理时间的元素。消息中间件通过 一个缓 冲层来帮助任务最高效率地执行，
写入消息中间件的处理会尽可能快速 。 该缓冲层有助于控制 和优化数据流经过系统的速度。

异步通信: 在很多时候应用不想也不需要立即处理消息 。消息中间件提供了异步处理机制， 允许应用把一些消息放入消息中间件中，
但并不立即处理它，在之后需要的时候再慢慢处理 。


消息中间件的缺点

系统可用性降低: 系统中加入了MQ，如果MQ挂了，整套系统奔溃了
系统复杂度提高：引入MQ会产生一些问题，比如怎么保证消息没有重复消费，怎么处理消息丢失的情况等

 *
 */

use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// todo 换成自己的配置
$host = '192.168.99.88';
$port = 5672;
$username = 'admin';
$password = 'admin';
$vhost = 'my_vhost';

// 1、连接到 RabbitMQ Broker，建立一个连接
$connection = new AMQPStreamConnection($host, $port, $username, $password, $vhost);

// 2、开启一个通道
$channel = $connection->channel();
$channel->confirm_select();



$exchange = 'test_exchange_confirm';
$queue = 'test_queue_confirm';
$route_key = 'test_confirm';

// 3、声明一个交换器，并且设置相关属性
$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

// 4、声明一个队列, 并且设置相关属性
$channel->queue_declare($queue, false, true, false, false);

// 5、通过路由键将交换器和队列绑定起来
$channel->queue_bind($queue, $exchange,$route_key);


function process_message($message)
{

    echo "\n--------\n";
    echo $message->body;
    echo "\n--------\n";

    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    // Send a message with the string "quit" to cancel the consumer.
    if ($message->body === 'quit') {
        $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
    }
}

// 6、消费消息，并且设置回调函数为 process_message
$channel->basic_consume($queue, 'consumer_tag', false, false, false, false, 'process_message');

// 7、注册终止函数，关闭通道，关闭连接
function shutdown($channel, $connection)
{
    $channel->close();
    $connection->close();
}
register_shutdown_function('shutdown', $channel, $connection);

// 8、一直阻塞消费数据
while ($channel ->is_consuming()) {
    $channel->wait();
}


/**
PhpAmqpLib\Message\AMQPMessage Object
(
[body] => Hello RabbitMQ
[body_size] => 14
[is_truncated] =>
[content_encoding] =>
[delivery_info] => Array
(
[channel] => PhpAmqpLib\Channel\AMQPChannel Object
(
[callbacks] => Array
(
[consumer_tag] => process_message
)

[is_open:protected] => 1
[default_ticket:protected] => 0
[active:protected] => 1
[alerts:protected] => Array
(
)

[auto_decode:protected] => 1
[basic_return_callback:protected] =>
[batch_messages:protected] => Array
(
)

[published_messages:PhpAmqpLib\Channel\AMQPChannel:private] => Array
(
)

[next_delivery_tag:PhpAmqpLib\Channel\AMQPChannel:private] => 0
[ack_handler:PhpAmqpLib\Channel\AMQPChannel:private] =>
[nack_handler:PhpAmqpLib\Channel\AMQPChannel:private] =>
[publish_cache:PhpAmqpLib\Channel\AMQPChannel:private] => Array
(
)

[publish_cache_max_size:PhpAmqpLib\Channel\AMQPChannel:private] => 100
[channel_rpc_timeout:protected] => 0
[frame_queue:protected] => Array
(
)

[method_queue:protected] => Array
(
)

[constants:protected] => PhpAmqpLib\Wire\Constants091 Object
(
)

[debug:protected] => PhpAmqpLib\Helper\DebugHelper Object
(
[debug:protected] =>
[debug_output:protected] => Resource id #2
[constants:protected] => PhpAmqpLib\Wire\Constants091 Object
(
)

)

[connection:protected] => PhpAmqpLib\Connection\AMQPStreamConnection Object
(
[channels] => Array
(
[0] => PhpAmqpLib\Connection\AMQPStreamConnection Object
 *RECURSION*
[1] => PhpAmqpLib\Channel\AMQPChannel Object
 *RECURSION*
)

[version_major:protected] => 0
[version_minor:protected] => 9
[server_properties:protected] => Array
(
[capabilities] => Array
(
[0] => F
[1] => Array
(
[publisher_confirms] => Array
(
[0] => t
[1] => 1
)

[exchange_exchange_bindings] => Array
(
[0] => t
[1] => 1
)

[basic.nack] => Array
(
[0] => t
[1] => 1
)

[consumer_cancel_notify] => Array
(
[0] => t
[1] => 1
)

[connection.blocked] => Array
(
[0] => t
[1] => 1
)

[consumer_priorities] => Array
(
[0] => t
[1] => 1
)

[authentication_failure_close] => Array
(
[0] => t
[1] => 1
)

[per_consumer_qos] => Array
(
[0] => t
[1] => 1
)

[direct_reply_to] => Array
(
[0] => t
[1] => 1
)

)

)

[cluster_name] => Array
(
[0] => S
[1] => rabbit@myRabbit
)

[copyright] => Array
(
[0] => S
[1] => Copyright (C) 2007-2018 Pivotal Software, Inc.
)

[information] => Array
(
[0] => S
[1] => Licensed under the MPL.  See http://www.rabbitmq.com/
)

[platform] => Array
(
[0] => S
[1] => Erlang/OTP 20.3.8.5
)

[product] => Array
(
[0] => S
[1] => RabbitMQ
)

[version] => Array
(
[0] => S
[1] => 3.7.7
)

)

[mechanisms:protected] => Array
(
[0] => AMQPLAIN
[1] => PLAIN
)

[locales:protected] => Array
(
[0] => en_US
)

[wait_tune_ok:protected] =>
[known_hosts:protected] =>
[input:protected] => PhpAmqpLib\Wire\AMQPReader Object
(
[str:protected] =>
[str_length:protected] => 0
[offset:protected] => 721
[bitcount:protected] => 0
[timeout:protected] => 0
[bits:protected] => 0
[io:protected] => PhpAmqpLib\Wire\IO\StreamIO Object
(
[protocol:protected] => tcp
[context:protected] => Resource id #59
[sock:PhpAmqpLib\Wire\IO\StreamIO:private] => Resource id #64
[host:protected] => 192.168.99.88
[port:protected] => 5672
[connection_timeout:protected] => 3
[read_timeout:protected] => 3
[write_timeout:protected] => 3
[heartbeat:protected] => 0
[initial_heartbeat:protected] => 0
[keepalive:protected] =>
[last_read:protected] => 1591842738.1408
[last_write:protected] => 1591842738.1401
[last_error:protected] =>
[canDispatchPcntlSignal:protected] => 1
)

[is64bits:protected] => 1
)

[vhost:protected] => my_vhost
[insist:protected] =>
[login_method:protected] => AMQPLAIN
[login_response:protected] => LOGINSadmiPASSWORDSadmin
[locale:protected] => en_US
[heartbeat:protected] => 0
[last_frame:protected] => 1591842738.1408
[channel_max:protected] => 2047
[frame_max:protected] => 131072
[construct_params:protected] => Array
(
[0] => 192.168.99.88
[1] => 5672
[2] => admin
[3] => admin
[4] => my_vhost
)

[close_on_destruct:protected] => 1
[is_connected:protected] => 1
[io:protected] => PhpAmqpLib\Wire\IO\StreamIO Object
(
[protocol:protected] => tcp
[context:protected] => Resource id #59
[sock:PhpAmqpLib\Wire\IO\StreamIO:private] => Resource id #64
[host:protected] => 192.168.99.88
[port:protected] => 5672
[connection_timeout:protected] => 3
[read_timeout:protected] => 3
[write_timeout:protected] => 3
[heartbeat:protected] => 0
[initial_heartbeat:protected] => 0
[keepalive:protected] =>
[last_read:protected] => 1591842738.1408
[last_write:protected] => 1591842738.1401
[last_error:protected] =>
[canDispatchPcntlSignal:protected] => 1
)

[wait_frame_reader:protected] => PhpAmqpLib\Wire\AMQPReader Object
(
[str:protected] =>
[str_length:protected] => 0
[offset:protected] => 15
[bitcount:protected] => 0
[timeout:protected] => 0
[bits:protected] => 0
[io:protected] =>
[is64bits:protected] => 1
)

[connection_block_handler:PhpAmqpLib\Connection\AbstractConnection:private] =>
[connection_unblock_handler:PhpAmqpLib\Connection\AbstractConnection:private] =>
[connection_timeout:protected] => 3
[prepare_content_cache:PhpAmqpLib\Connection\AbstractConnection:private] => Array
(
)

[prepare_content_cache_max_size:PhpAmqpLib\Connection\AbstractConnection:private] => 100
[channel_rpc_timeout:PhpAmqpLib\Connection\AbstractConnection:private] => 0
[blocked:protected] =>
[frame_queue:protected] => Array
(
)

[method_queue:protected] => Array
(
)

[auto_decode:protected] =>
[constants:protected] => PhpAmqpLib\Wire\Constants091 Object
(
)

[debug:protected] => PhpAmqpLib\Helper\DebugHelper Object
(
[debug:protected] =>
[debug_output:protected] => Resource id #2
[constants:protected] => PhpAmqpLib\Wire\Constants091 Object
(
)

)

[connection:protected] => PhpAmqpLib\Connection\AMQPStreamConnection Object
 *RECURSION*
[protocolVersion:protected] => 0.9.1
[maxBodySize:protected] =>
[protocolWriter:protected] => PhpAmqpLib\Helper\Protocol\Protocol091 Object
(
)

[waitHelper:protected] => PhpAmqpLib\Helper\Protocol\Wait091 Object
(
[wait:protected] => Array
(
[connection.start] => 10,10
[connection.start_ok] => 10,11
[connection.secure] => 10,20
[connection.secure_ok] => 10,21
[connection.tune] => 10,30
[connection.tune_ok] => 10,31
[connection.open] => 10,40
[connection.open_ok] => 10,41
[connection.close] => 10,50
[connection.close_ok] => 10,51
[connection.blocked] => 10,60
[connection.unblocked] => 10,61
[channel.open] => 20,10
[channel.open_ok] => 20,11
[channel.flow] => 20,20
[channel.flow_ok] => 20,21
[channel.close] => 20,40
[channel.close_ok] => 20,41
[access.request] => 30,10
[access.request_ok] => 30,11
[exchange.declare] => 40,10
[exchange.declare_ok] => 40,11
[exchange.delete] => 40,20
[exchange.delete_ok] => 40,21
[exchange.bind] => 40,30
[exchange.bind_ok] => 40,31
[exchange.unbind] => 40,40
[exchange.unbind_ok] => 40,51
[queue.declare] => 50,10
[queue.declare_ok] => 50,11
[queue.bind] => 50,20
[queue.bind_ok] => 50,21
[queue.purge] => 50,30
[queue.purge_ok] => 50,31
[queue.delete] => 50,40
[queue.delete_ok] => 50,41
[queue.unbind] => 50,50
[queue.unbind_ok] => 50,51
[basic.qos] => 60,10
[basic.qos_ok] => 60,11
[basic.consume] => 60,20
[basic.consume_ok] => 60,21
[basic.cancel] => 60,30
[basic.cancel_ok] => 60,31
[basic.publish] => 60,40
[basic.return] => 60,50
[basic.deliver] => 60,60
[basic.get] => 60,70
[basic.get_ok] => 60,71
[basic.get_empty] => 60,72
[basic.ack] => 60,80
[basic.reject] => 60,90
[basic.recover_async] => 60,100
[basic.recover] => 60,110
[basic.recover_ok] => 60,111
[basic.nack] => 60,120
[tx.select] => 90,10
[tx.select_ok] => 90,11
[tx.commit] => 90,20
[tx.commit_ok] => 90,21
[tx.rollback] => 90,30
[tx.rollback_ok] => 90,31
[confirm.select] => 85,10
[confirm.select_ok] => 85,11
)

)

[methodMap:protected] => PhpAmqpLib\Helper\Protocol\MethodMap091 Object
(
[method_map:protected] => Array
(
[10,10] => connection_start
[10,11] => connection_start_ok
[10,20] => connection_secure
[10,21] => connection_secure_ok
[10,30] => connection_tune
[10,31] => connection_tune_ok
[10,40] => connection_open
[10,41] => connection_open_ok
[10,50] => connection_close
[10,51] => connection_close_ok
[10,60] => connection_blocked
[10,61] => connection_unblocked
[20,10] => channel_open
[20,11] => channel_open_ok
[20,20] => channel_flow
[20,21] => channel_flow_ok
[20,40] => channel_close
[20,41] => channel_close_ok
[30,10] => access_request
[30,11] => access_request_ok
[40,10] => exchange_declare
[40,11] => exchange_declare_ok
[40,20] => exchange_delete
[40,21] => exchange_delete_ok
[40,30] => exchange_bind
[40,31] => exchange_bind_ok
[40,40] => exchange_unbind
[40,51] => exchange_unbind_ok
[50,10] => queue_declare
[50,11] => queue_declare_ok
[50,20] => queue_bind
[50,21] => queue_bind_ok
[50,30] => queue_purge
[50,31] => queue_purge_ok
[50,40] => queue_delete
[50,41] => queue_delete_ok
[50,50] => queue_unbind
[50,51] => queue_unbind_ok
[60,10] => basic_qos
[60,11] => basic_qos_ok
[60,20] => basic_consume
[60,21] => basic_consume_ok
[60,30] => basic_cancel_from_server
[60,31] => basic_cancel_ok
[60,40] => basic_publish
[60,50] => basic_return
[60,60] => basic_deliver
[60,70] => basic_get
[60,71] => basic_get_ok
[60,72] => basic_get_empty
[60,80] => basic_ack_from_server
[60,90] => basic_reject
[60,100] => basic_recover_async
[60,110] => basic_recover
[60,111] => basic_recover_ok
[60,120] => basic_nack_from_server
[90,10] => tx_select
[90,11] => tx_select_ok
[90,20] => tx_commit
[90,21] => tx_commit_ok
[90,30] => tx_rollback
[90,31] => tx_rollback_ok
[85,10] => confirm_select
[85,11] => confirm_select_ok
)

)

[channel_id:protected] => 0
[msg_property_reader:protected] => PhpAmqpLib\Wire\AMQPReader Object
(
[str:protected] =>
[str_length:protected] => 0
[offset:protected] => 0
[bitcount:protected] => 0
[timeout:protected] => 0
[bits:protected] => 0
[io:protected] =>
[is64bits:protected] => 1
)

[wait_content_reader:protected] => PhpAmqpLib\Wire\AMQPReader Object
(
[str:protected] =>
[str_length:protected] => 0
[offset:protected] => 0
[bitcount:protected] => 0
[timeout:protected] => 0
[bits:protected] => 0
[io:protected] =>
[is64bits:protected] => 1
)

[dispatch_reader:protected] => PhpAmqpLib\Wire\AMQPReader Object
(
[str:protected] =>
[str_length:protected] => 0
[offset:protected] => 1
[bitcount:protected] => 0
[timeout:protected] => 0
[bits:protected] => 0
[io:protected] =>
[is64bits:protected] => 1
)

)

[protocolVersion:protected] => 0.9.1
[maxBodySize:protected] =>
[protocolWriter:protected] => PhpAmqpLib\Helper\Protocol\Protocol091 Object
(
)

[waitHelper:protected] => PhpAmqpLib\Helper\Protocol\Wait091 Object
(
[wait:protected] => Array
(
[connection.start] => 10,10
[connection.start_ok] => 10,11
[connection.secure] => 10,20
[connection.secure_ok] => 10,21
[connection.tune] => 10,30
[connection.tune_ok] => 10,31
[connection.open] => 10,40
[connection.open_ok] => 10,41
[connection.close] => 10,50
[connection.close_ok] => 10,51
[connection.blocked] => 10,60
[connection.unblocked] => 10,61
[channel.open] => 20,10
[channel.open_ok] => 20,11
[channel.flow] => 20,20
[channel.flow_ok] => 20,21
[channel.close] => 20,40
[channel.close_ok] => 20,41
[access.request] => 30,10
[access.request_ok] => 30,11
[exchange.declare] => 40,10
[exchange.declare_ok] => 40,11
[exchange.delete] => 40,20
[exchange.delete_ok] => 40,21
[exchange.bind] => 40,30
[exchange.bind_ok] => 40,31
[exchange.unbind] => 40,40
[exchange.unbind_ok] => 40,51
[queue.declare] => 50,10
[queue.declare_ok] => 50,11
[queue.bind] => 50,20
[queue.bind_ok] => 50,21
[queue.purge] => 50,30
[queue.purge_ok] => 50,31
[queue.delete] => 50,40
[queue.delete_ok] => 50,41
[queue.unbind] => 50,50
[queue.unbind_ok] => 50,51
[basic.qos] => 60,10
[basic.qos_ok] => 60,11
[basic.consume] => 60,20
[basic.consume_ok] => 60,21
[basic.cancel] => 60,30
[basic.cancel_ok] => 60,31
[basic.publish] => 60,40
[basic.return] => 60,50
[basic.deliver] => 60,60
[basic.get] => 60,70
[basic.get_ok] => 60,71
[basic.get_empty] => 60,72
[basic.ack] => 60,80
[basic.reject] => 60,90
[basic.recover_async] => 60,100
[basic.recover] => 60,110
[basic.recover_ok] => 60,111
[basic.nack] => 60,120
[tx.select] => 90,10
[tx.select_ok] => 90,11
[tx.commit] => 90,20
[tx.commit_ok] => 90,21
[tx.rollback] => 90,30
[tx.rollback_ok] => 90,31
[confirm.select] => 85,10
[confirm.select_ok] => 85,11
)

)

[methodMap:protected] => PhpAmqpLib\Helper\Protocol\MethodMap091 Object
(
[method_map:protected] => Array
(
[10,10] => connection_start
[10,11] => connection_start_ok
[10,20] => connection_secure
[10,21] => connection_secure_ok
[10,30] => connection_tune
[10,31] => connection_tune_ok
[10,40] => connection_open
[10,41] => connection_open_ok
[10,50] => connection_close
[10,51] => connection_close_ok
[10,60] => connection_blocked
[10,61] => connection_unblocked
[20,10] => channel_open
[20,11] => channel_open_ok
[20,20] => channel_flow
[20,21] => channel_flow_ok
[20,40] => channel_close
[20,41] => channel_close_ok
[30,10] => access_request
[30,11] => access_request_ok
[40,10] => exchange_declare
[40,11] => exchange_declare_ok
[40,20] => exchange_delete
[40,21] => exchange_delete_ok
[40,30] => exchange_bind
[40,31] => exchange_bind_ok
[40,40] => exchange_unbind
[40,51] => exchange_unbind_ok
[50,10] => queue_declare
[50,11] => queue_declare_ok
[50,20] => queue_bind
[50,21] => queue_bind_ok
[50,30] => queue_purge
[50,31] => queue_purge_ok
[50,40] => queue_delete
[50,41] => queue_delete_ok
[50,50] => queue_unbind
[50,51] => queue_unbind_ok
[60,10] => basic_qos
[60,11] => basic_qos_ok
[60,20] => basic_consume
[60,21] => basic_consume_ok
[60,30] => basic_cancel_from_server
[60,31] => basic_cancel_ok
[60,40] => basic_publish
[60,50] => basic_return
[60,60] => basic_deliver
[60,70] => basic_get
[60,71] => basic_get_ok
[60,72] => basic_get_empty
[60,80] => basic_ack_from_server
[60,90] => basic_reject
[60,100] => basic_recover_async
[60,110] => basic_recover
[60,111] => basic_recover_ok
[60,120] => basic_nack_from_server
[90,10] => tx_select
[90,11] => tx_select_ok
[90,20] => tx_commit
[90,21] => tx_commit_ok
[90,30] => tx_rollback
[90,31] => tx_rollback_ok
[85,10] => confirm_select
[85,11] => confirm_select_ok
)

)

[channel_id:protected] => 1
[msg_property_reader:protected] => PhpAmqpLib\Wire\AMQPReader Object
(
[str:protected] =>
[str_length:protected] => 0
[offset:protected] => 3
[bitcount:protected] => 0
[timeout:protected] => 0
[bits:protected] => 0
[io:protected] =>
[is64bits:protected] => 1
)

[wait_content_reader:protected] => PhpAmqpLib\Wire\AMQPReader Object
(
[str:protected] =>
[str_length:protected] => 0
[offset:protected] => 12
[bitcount:protected] => 0
[timeout:protected] => 0
[bits:protected] => 0
[io:protected] =>
[is64bits:protected] => 1
)

[dispatch_reader:protected] => PhpAmqpLib\Wire\AMQPReader Object
(
[str:protected] =>
[str_length:protected] => 0
[offset:protected] => 37
[bitcount:protected] => 0
[timeout:protected] => 0
[bits:protected] => 0
[io:protected] =>
[is64bits:protected] => 1
)

)

[consumer_tag] => consumer_tag
[delivery_tag] => 1
[redelivered] =>
[exchange] => test_exchange
[routing_key] =>
)

[prop_types:protected] => Array
(
[content_type] => shortstr
[content_encoding] => shortstr
[application_headers] => table_object
[delivery_mode] => octet
[priority] => octet
[correlation_id] => shortstr
[reply_to] => shortstr
[expiration] => shortstr
[message_id] => shortstr
[timestamp] => timestamp
[type] => shortstr
[user_id] => shortstr
[app_id] => shortstr
[cluster_id] => shortstr
)

[properties:PhpAmqpLib\Wire\GenericContent:private] => Array
(
[delivery_mode] => 2
)

[serialized_properties:PhpAmqpLib\Wire\GenericContent:private] =>
)

--------
Hello RabbitMQ
--------

 */