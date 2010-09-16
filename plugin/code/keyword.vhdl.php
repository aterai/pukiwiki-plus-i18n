<?php
/**
 * VHDL キーワード定義ファイル
 */

$switchHash['\''] = PLUGIN_CODE_SPECIAL_IDENTIFIRE;  // ' は予約語
$capital = true;                         // 予約語の大文字小文字を区別しない
$mkoutline = $option['outline'] = false; // アウトラインモード不可 

// コメント定義
$switchHash['-'] = PLUGIN_CODE_COMMENT;    // コメントは -- から改行まで
$code_comment = Array(
	'-' => Array(
				 Array('/^--/', "\n", 1),
	)
);

$code_css = Array(
  'operator',		// オペレータ関数
  'identifier',	// その他の識別子
  'pragma',		// module, import と pragma
  'system',		// 処理系組み込みの奴 __stdcall とか
  );

$code_keyword = Array(
  'access' => 2,
  'after' => 2,
  'alias' => 2,
  'all' => 2,
  'assert' => 2,
  'architecture' => 2,
  'array' => 2,
  'attribute' => 2,
  'begin' => 2,
  'block' => 2,
  'body' => 2,
  'buffer' => 2,
  'bus' => 2,
  'case' => 2,
  'component' => 2,
  'configuration' => 2,
  'constant' => 2,
  'disconnect' => 2,
  'downto' => 2,
  'elsif' => 2,
  'end' => 2,
  'entity' => 2,
  'exit' => 2,
  'if' => 2,
  'else' => 2,
  'file' => 2,
  'for' => 2,
  'function' => 2,
  'generate' => 2,
  'generic' => 2,
  'group' => 2,
  'guarded' => 2,
  'impure' => 2,
  'in' => 2,
  'inertial' => 2,
  'inout' => 2,
  'is' => 2,
  'label' => 2,
  'library' => 2,
  'linkage' => 2,
  'literal' => 2,
  'loop' => 2,
  'map' => 2,
  'new' => 2,
  'next' => 2,
  'null' => 2,
  'of' => 2,
  'on' => 2,
  'open' => 2,
  'others' => 2,
  'out' => 2,
  'package' => 2,
  'port' => 2,
  'postponed' => 2,
  'procedure' => 2,
  'process' => 2,
  'pure' => 2,
  'range' => 2,
  'record' => 2,
  'register' => 2,
  'reject' => 2,
  'report' => 2,
  'return' => 2,
  'select' => 2,
  'severity' => 2,
  'signal' => 2,
  'shared' => 2,
  'subtype' => 2,
  'then' => 2,
  'to' => 2,
  'transport' => 2,
  'type' => 2,
  'unaffected' => 2,
  'units' => 2,
  'until' => 2,
  'use' => 2,
  'variable' => 2,
  'wait' => 2,
  'when' => 2,
  'while' => 2,
  'with' => 2,
  'note' => 2,
  'warning' => 2,
  'error' => 2,
  'failure' => 2,
  'bit' => 2,
  'bit_vector' => 2,
  'character' => 2,
  'boolean' => 2,
  'integer' => 2,
  'real' => 2,
  'time' => 2,
  'string' => 2,
  'severity_level' => 2,
  'positive' => 2,
  'natural' => 2,
  'signed' => 2,
  'unsigned' => 2,
  'line' => 2,
  'text' => 2,
  'std_logic' => 2,
  'std_logic_vector' => 2,
  'std_ulogic' => 2,
  'std_ulogic_vector' => 2,
  'qsim_state' => 2,
  'qsim_state_vector' => 2,
  'qsim_12state' => 2,
  'qsim_12state_vector' => 2,
  'qsim_strength' => 2,
  'mux_bit' => 2,
  'mux_vector' => 2,
  'reg_bit' => 2,
  'reg_vector' => 2,
  'wor_bit' => 2,
  'wor_vector' => 2,

  '\'high' => 2,
  '\'left' => 2,
  '\'length' => 2,
  '\'low' => 2,
  '\'range' => 2,
  '\'reverse_range' => 2,
  '\'right' => 2,
  '\'ascending' => 2,
  '\'behaviour' => 2,
  '\'structure' => 2,
  '\'simple_name' => 2,
  '\'instance_name' => 2,
  '\'path_name' => 2,
  '\'foreign' => 2,
  '\'active' => 2,
  '\'delayed' => 2,
  '\'event' => 2,
  '\'last_active' => 2,
  '\'last_event' => 2,
  '\'last_value' => 2,
  '\'quiet' => 2,
  '\'stable' => 2,
  '\'transaction' => 2,
  '\'driving' => 2,
  '\'driving_value' => 2,
  '\'base' => 2,
  '\'high' => 2,
  '\'left' => 2,
  '\'leftof' => 2,
  '\'low' => 2,
  '\'pos' => 2,
  '\'pred' => 2,
  '\'rightof' => 2,
  '\'succ' => 2,
  '\'val' => 2,
  '\'image' => 2,
  '\'value' => 2,

  'true' => 2,
  'false' => 2,
  'S0S' => 2,
  'S1S' => 2,
  'SXS' => 2,
  'S0R' => 2,
  'S1R' => 2,
  'SXR' => 2,
  'S0Z' => 2,
  'S1Z' => 2,
  'SXZ' => 2,
  'S0I' => 2,
  'S1I' => 2,
  'SXI' => 2,
  'and' => 2,
  'nand' => 2,
  'or' => 2,
  'nor' => 2,
  'xor' => 2,
  'xnor' => 2,
  'rol' => 2,
  'ror' => 2,
  'sla' => 2,
  'sll' => 2,
  'sra' => 2,
  'srl' => 2,
  'mod' => 2,
  'rem' => 2,
  'abs' => 2,
  'not' => 2,
  );
?>