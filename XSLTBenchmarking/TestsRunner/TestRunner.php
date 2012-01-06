<?php

/**
 * XSLT Benchmarking
 * @link https://github.com/masicek/XSLT-Benchmarking
 * @author Viktor Mašíček <viktor@masicek.net>
 * @license "New" BSD License
 */

namespace XSLTBenchmarking\TestsRunner;

/**
 * Class for run one test
 *
 * @author Viktor Mašíček <viktor@masicek.net>
 */
class TestRunner
{


	public function __construct(
		\XSLTBenchmarking\Factory $factory,
		$tmpDir
	)
	{
		// TODO nastaveni:
		// - seznam procesoru (include + exclude)
		// - pocet opakovani kazdeho testu
		//

		// TODO set needed settings

		// TODO nakonfigurovat objekt podle options
		// TODO trida na spusteni jednoho testu ve vsech moznych procesorech
		//      nastaveni (pocet opakovani, jake pouzit procesory, ...)

	}


	/**
	 * Run one test
	 *
	 * @param \XSLTBenchmarking\TestsRunner\Test $test
	 *
	 * @return \XSLTBenchmarking\Reports\Report
	 */
	public function run(\XSLTBenchmarking\TestsRunner\Test $test)
	{
		// TODO co merit: speed, memory usage, correctness
		// - cas jednoho parsovani (pro dany procesor)
		// - jestli je vystup korektni nebo ne (pres Corrector)
		// - pouzita pamet
		//
	}


}
