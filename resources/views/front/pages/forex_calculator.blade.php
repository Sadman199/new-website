@extends('front.layout.app')
@section('main_content')
<div class="bg-gradient-to-b from-gray-900 to-gray-800 flex items-center justify-center min-h-screen py-20">
    <div class="container px-4 max-w-7xl mx-auto w-full sm:px-6 lg:px-4">
        <!-- Calculator Card -->
        <div class="bg-gray-800/80 backdrop-blur-sm border border-gray-700 rounded-xl shadow-2xl overflow-hidden mt-20">
            <!-- Tabs -->
            <div class="flex border-b border-gray-700">
                <button id="tradeTab" class="flex-1 py-4 px-6 text-center font-medium text-white bg-blue-600/30 border-blue-500 tab-button active">
                    <i class="fas fa-calculator mr-2"></i>Trade Calculator
                </button>
                <button id="riskTab" class="flex-1 py-4 px-6 text-center font-medium text-gray-400 hover:text-white transition tab-button">
                    <i class="fas fa-chart-line mr-2"></i>Risk Calculator
                </button>
                <button id="pipTab" class="flex-1 py-4 px-6 text-center font-medium text-gray-400 hover:text-white transition tab-button">
                    <i class="fas fa-exchange-alt mr-2"></i>Pip Calculator
                </button>
            </div>

            <!-- Trade Calculator Content -->
            <div id="tradeContent" class="tab-content active">
                <div class="p-6 md:p-8">
                    <!-- Main Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Currency Pair -->
                            <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                                <label class="block text-sm font-semibold text-blue-400 mb-2">CURRENCY PAIR</label>
                                <div class="relative">
                                    <select id="currencyPair" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none transition duration-200">
                                        <option value="EUR/USD">EUR/USD</option>
                                        <option value="GBP/USD">GBP/USD</option>
                                        <option value="USD/JPY">USD/JPY</option>
                                        <option value="AUD/USD">AUD/USD</option>
                                        <option value="USD/CAD">USD/CAD</option>
                                        <option value="NZD/USD">NZD/USD</option>
                                        <option value="EUR/GBP">EUR/GBP</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Trade Direction -->
                            <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                                <label class="block text-sm font-semibold text-blue-400 mb-2">TRADE DIRECTION</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <button id="buyBtn" class="flex items-center justify-center bg-green-600/30 hover:bg-green-600/40 text-white font-medium py-3 px-4 rounded-lg border border-green-500 transition duration-200 active">
                                        <i class="fas fa-arrow-up mr-2"></i>
                                        Buy
                                    </button>
                                    <button id="sellBtn" class="flex items-center justify-center bg-red-600/30 hover:bg-red-600/40 text-white font-medium py-3 px-4 rounded-lg border border-red-500 transition duration-200">
                                        <i class="fas fa-arrow-down mr-2"></i>
                                        Sell
                                    </button>
                                </div>
                            </div>

                            <!-- Entry/Exit Price -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                                    <label for="entryPrice" class="block text-sm font-semibold text-blue-400 mb-2">ENTRY PRICE</label>
                                    <div class="relative">
                                        <input id="entryPrice" type="number" step="0.00001" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="1.12345" value="1.12345">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                                    <label for="exitPrice" class="block text-sm font-semibold text-blue-400 mb-2">EXIT PRICE</label>
                                    <div class="relative">
                                        <input id="exitPrice" type="number" step="0.00001" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="1.12500" value="1.12500">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Position Size -->
                            <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                                <label for="positionSize" class="block text-sm font-semibold text-blue-400 mb-2">POSITION SIZE (LOTS)</label>
                                <div class="relative">
                                    <input id="positionSize" type="number" step="0.01" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="0.1" value="0.1">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                </div>
                                <div class="flex justify-between mt-3 space-x-2">
                                    <button class="size-btn flex-1 text-xs bg-gray-700 hover:bg-gray-600 text-gray-200 px-2 py-2 rounded transition duration-200" data-size="0.01">0.01</button>
                                    <button class="size-btn flex-1 text-xs bg-gray-700 hover:bg-gray-600 text-gray-200 px-2 py-2 rounded transition duration-200" data-size="0.1">0.1</button>
                                    <button class="size-btn flex-1 text-xs bg-gray-700 hover:bg-gray-600 text-gray-200 px-2 py-2 rounded transition duration-200" data-size="1">1</button>
                                    <button class="size-btn flex-1 text-xs bg-gray-700 hover:bg-gray-600 text-gray-200 px-2 py-2 rounded transition duration-200" data-size="5">5</button>
                                    <button class="size-btn flex-1 text-xs bg-gray-700 hover:bg-gray-600 text-gray-200 px-2 py-2 rounded transition duration-200" data-size="10">10</button>
                                </div>
                            </div>

                            <!-- Account Settings -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                                    <label for="leverage" class="block text-sm font-semibold text-blue-400 mb-2">LEVERAGE</label>
                                    <div class="relative">
                                        <select id="leverage" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none transition duration-200">
                                            <option value="10">1:10</option>
                                            <option value="20">1:20</option>
                                            <option value="30">1:30</option>
                                            <option value="50" selected>1:50</option>
                                            <option value="100">1:100</option>
                                            <option value="200">1:200</option>
                                            <option value="500">1:500</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                            <i class="fas fa-expand-arrows-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                                    <label for="accountCurrency" class="block text-sm font-semibold text-blue-400 mb-2">ACCOUNT CURRENCY</label>
                                    <div class="relative">
                                        <select id="accountCurrency" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none transition duration-200">
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                            <option value="GBP">GBP</option>
                                            <option value="JPY">JPY</option>
                                            <option value="AUD">AUD</option>
                                            <option value="CAD">CAD</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Risk Management -->
                            <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                                <label class="block text-sm font-semibold text-blue-400 mb-2">RISK MANAGEMENT</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="riskPercent" class="block text-xs text-gray-400 mb-1">Risk %</label>
                                        <input id="riskPercent" type="number" step="0.1" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="1.0" value="1.0">
                                    </div>
                                    <div>
                                        <label for="accountBalance" class="block text-xs text-gray-400 mb-1">Account Balance</label>
                                        <div class="relative">
                                            <input id="accountBalance" type="number" step="1" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-3 py-2 pl-8 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="1000" value="1000">
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-2 text-gray-400 text-xs">
                                                <span id="accountCurrencySymbol">$</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Results Section -->
                    <div class="mt-8 bg-gray-900/50 border border-gray-700 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-chart-pie mr-2 text-blue-400"></i>
                            TRADE SUMMARY
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-gray-800/70 p-4 rounded-lg border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">PIP Value</p>
                                <p id="pipValue" class="text-xl font-bold text-white">$0.00</p>
                            </div>
                            <div class="bg-gray-800/70 p-4 rounded-lg border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">PIPs</p>
                                <p id="pips" class="text-xl font-bold text-white">0.0</p>
                            </div>
                            <div class="bg-gray-800/70 p-4 rounded-lg border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Profit/Loss</p>
                                <p id="profitLoss" class="text-xl font-bold text-green-400">$0.00</p>
                            </div>
                            <div class="bg-gray-800/70 p-4 rounded-lg border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Margin</p>
                                <p id="margin" class="text-xl font-bold text-white">$0.00</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div class="bg-gray-800/70 p-4 rounded-lg border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Risk Amount</p>
                                <p id="riskAmount" class="text-lg font-bold text-white">$10.00</p>
                            </div>
                            <div class="bg-gray-800/70 p-4 rounded-lg border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Reward/Risk</p>
                                <p id="rewardRisk" class="text-lg font-bold text-white">1:1</p>
                            </div>
                            <div class="bg-gray-800/70 p-4 rounded-lg border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Position Value</p>
                                <p id="positionValue" class="text-lg font-bold text-white">$0.00</p>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-8 flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                        <button id="resetBtn" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-redo mr-2"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Risk Calculator Content -->
            <div id="riskContent" class="tab-content hidden">
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Risk Parameters -->
                            <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                                <label class="block text-sm font-semibold text-blue-400 mb-2">RISK PARAMETERS</label>
                                <div class="space-y-4">
                                    <div>
                                        <label for="riskAccountBalance" class="block text-xs text-gray-400 mb-1">Account Balance</label>
                                        <div class="relative">
                                            <input id="riskAccountBalance" type="number" step="1" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 pl-8 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="1000" value="1000">
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                                <span id="riskAccountCurrencySymbol">$</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="riskPercentage" class="block text-xs text-gray-400 mb-1">Risk Percentage</label>
                                        <input id="riskPercentage" type="number" step="0.1" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="1.0" value="1.0">
                                    </div>
                                    <div>
                                        <label for="stopLossPips" class="block text-xs text-gray-400 mb-1">Stop Loss (Pips)</label>
                                        <input id="stopLossPips" type="number" step="1" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="20" value="20">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Currency Pair -->
                            <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                                <label class="block text-sm font-semibold text-blue-400 mb-2">CURRENCY PAIR</label>
                                <div class="relative">
                                    <select id="riskCurrencyPair" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none transition duration-200">
                                        <option value="EUR/USD">EUR/USD</option>
                                        <option value="GBP/USD">GBP/USD</option>
                                        <option value="USD/JPY">USD/JPY</option>
                                        <option value="AUD/USD">AUD/USD</option>
                                        <option value="USD/CAD">USD/CAD</option>
                                        <option value="NZD/USD">NZD/USD</option>
                                        <option value="EUR/GBP">EUR/GBP</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Currency -->
                            <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                                <label for="riskAccountCurrency" class="block text-sm font-semibold text-blue-400 mb-2">ACCOUNT CURRENCY</label>
                                <div class="relative">
                                    <select id="riskAccountCurrency" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none transition duration-200">
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                        <option value="GBP">GBP</option>
                                        <option value="JPY">JPY</option>
                                        <option value="AUD">AUD</option>
                                        <option value="CAD">CAD</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Results Section -->
                    <div class="mt-8 bg-gray-900/50 border border-gray-700 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-shield-alt mr-2 text-blue-400"></i>
                            RISK SUMMARY
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-800/70 p-4 rounded-lg border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Risk Amount</p>
                                <p id="calculatedRiskAmount" class="text-xl font-bold text-white">$10.00</p>
                            </div>
                            <div class="bg-gray-800/70 p-4 rounded-lg border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Position Size</p>
                                <p id="calculatedPositionSize" class="text-xl font-bold text-white">0.10 lots</p>
                            </div>
                            <div class="bg-gray-800/70 p-4 rounded-lg border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Pip Value</p>
                                <p id="calculatedPipValue" class="text-xl font-bold text-white">$1.00</p>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-8 flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                        <button id="resetRiskBtn" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-redo mr-2"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pip Calculator Content -->
            <div id="pipContent" class="tab-content hidden">
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Pip Calculation -->
                            <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                                <label class="block text-sm font-semibold text-blue-400 mb-2">PIP CALCULATION</label>
                                <div class="space-y-4">
                                    <div>
                                        <label for="pipCurrencyPair" class="block text-xs text-gray-400 mb-1">Currency Pair</label>
                                        <div class="relative">
                                            <select id="pipCurrencyPair" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none transition duration-200">
                                                <option value="EUR/USD">EUR/USD</option>
                                                <option value="GBP/USD">GBP/USD</option>
                                                <option value="USD/JPY">USD/JPY</option>
                                                <option value="AUD/USD">AUD/USD</option>
                                                <option value="USD/CAD">USD/CAD</option>
                                                <option value="NZD/USD">NZD/USD</option>
                                                <option value="EUR/GBP">EUR/GBP</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="pipPositionSize" class="block text-xs text-gray-400 mb-1">Position Size (Lots)</label>
                                        <div class="relative">
                                            <input id="pipPositionSize" type="number" step="0.01" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="0.1" value="0.1">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                                <i class="fas fa-layer-group"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Account Settings -->
                            <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                                <label class="block text-sm font-semibold text-blue-400 mb-2">ACCOUNT SETTINGS</label>
                                <div class="space-y-4">
                                    <div>
                                        <label for="pipAccountCurrency" class="block text-xs text-gray-400 mb-1">Account Currency</label>
                                        <div class="relative">
                                            <select id="pipAccountCurrency" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none transition duration-200">
                                                <option value="USD">USD</option>
                                                <option value="EUR">EUR</option>
                                                <option value="GBP">GBP</option>
                                                <option value="JPY">JPY</option>
                                                <option value="AUD">AUD</option>
                                                <option value="CAD">CAD</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="pipCurrentPrice" class="block text-xs text-gray-400 mb-1">Current Price (Optional)</label>
                                        <div class="relative">
                                            <input id="pipCurrentPrice" type="number" step="0.00001" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="1.12345">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                                <i class="fas fa-tag"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Results Section -->
                    <div class="mt-8 bg-gray-900/50 border border-gray-700 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-exchange-alt mr-2 text-blue-400"></i>
                            PIP VALUE SUMMARY
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-800/70 p-4 rounded-lg border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">PIP Value</p>
                                <p id="calculatedPipValueResult" class="text-xl font-bold text-white">$1.00</p>
                            </div>
                            <div class="bg-gray-800/70 p-4 rounded-lg border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">PIP Size</p>
                                <p id="calculatedPipSize" class="text-xl font-bold text-white">0.0001</p>
                            </div>
                            <div class="bg-gray-800/70 p-4 rounded-lg border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Position Value</p>
                                <p id="calculatedPipPositionValue" class="text-xl font-bold text-white">$10,000.00</p>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-8 flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                        <button id="resetPipBtn" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-redo mr-2"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Font Awesome for icons -->
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1f2937;
        }
        ::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #2563eb;
        }
        
        /* Input number arrows */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none;
            margin: 0;
        }
        
        /* Tab transitions */
        .tab-content {
            transition: opacity 0.3s ease;
        }
    </style>
</div>
@endsection