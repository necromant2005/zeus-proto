### Generated by rprotoc. DO NOT EDIT!
### <proto file: Test1.proto>
# message Test1 {
#   required int32 a = 1;
# }
#

require 'protobuf/message/message'
require 'protobuf/message/enum'
require 'protobuf/message/service'
require 'protobuf/message/extend'

class Test1 < ::Protobuf::Message
  defined_in __FILE__
  required :int32, :a, 1
end

ce = Test1.new
ce.a = 150
print ce.to_s
