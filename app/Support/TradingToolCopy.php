<?php

namespace App\Support;

class TradingToolCopy
{
    /**
     * Default editorial copy used when admin fields are empty.
     *
     * @return array{introduction?: string, how_to_use?: string, formula?: string, example?: string, additional?: string, faqs?: array<int, array{question: string, answer: string}>}
     */
    public static function for(string $toolKey): array
    {
        return match ($toolKey) {
            'pip' => [
                'introduction' => 'A pip is the standard increment used to measure a forex price move. This calculator converts that move into money in your account currency so you can see what a 1-pip change is worth at your lot size.',
                'how_to_use' => 'Choose the currency pair, enter the lot size, and select your account currency. Leave market price blank to use a reference rate, or type the current quote if you want a tighter estimate.',
                'formula' => 'Pip value = pip size × contract size × lots, then converted from the quote currency into your account currency. Most pairs use a 0.0001 pip; yen pairs use 0.01.',
                'example' => 'On EUR/USD, 1.00 standard lot, and a USD account, each pip is typically worth about $10. A 15-pip move is then about $150 before spread or commission.',
                'additional' => 'Lot size scales pip value linearly: 0.10 lot is one-tenth of a standard lot. Always confirm the contract size and quote with your broker before trading.',
                'faqs' => [
                    ['question' => 'What is a pip in forex?', 'answer' => 'A pip is the usual unit of price movement. On most pairs it is the fourth decimal place (0.0001). On yen pairs it is the second decimal place (0.01).'],
                    ['question' => 'How does lot size affect pip value?', 'answer' => 'Pip value rises in proportion to lot size. Doubling the lots doubles the money gained or lost per pip, which is why position sizing matters as much as the pair itself.'],
                    ['question' => 'Why does account currency change the result?', 'answer' => 'The raw pip value is in the pair’s quote currency. The calculator converts that amount into the currency your account is denominated in so the number matches your balance.'],
                ],
            ],
            'position' => [
                'introduction' => 'Position size turns a risk plan into a lot size. Enter your balance, the percentage you are willing to lose, and the stop-loss distance in pips.',
                'how_to_use' => 'Fill in account balance, risk percent, stop-loss pips, and the pair. The calculator uses pip value for that pair so the suggested lots keep the loss near your chosen amount if the stop is hit.',
                'formula' => 'Risk amount = balance × (risk percent ÷ 100). Lots = risk amount ÷ (stop-loss pips × pip value per lot).',
                'example' => 'On a $10,000 account risking 1% with a 20-pip stop, risk amount is $100. If pip value is $10 per lot, the position size is 0.50 lots.',
                'additional' => 'This is a planning figure. Broker contract size, spread, and slippage can change the realised loss. Recalculate if you change the pair or the stop.',
                'faqs' => [
                    ['question' => 'Why size a position from risk instead of a fixed lot?', 'answer' => 'A fixed lot can risk very different amounts on different pairs and stops. Sizing from a percentage keeps each trade in line with the same account-risk rule.'],
                    ['question' => 'What if my stop is wider?', 'answer' => 'A wider stop needs a smaller lot size to keep the same cash risk. The calculator reduces lots as stop-loss pips increase.'],
                    ['question' => 'Should I include spread in the stop?', 'answer' => 'Many traders add the spread to the stop distance so the risk amount covers the cost of getting filled. You can do that by increasing the stop-loss pips you enter.'],
                ],
            ],
            'profit' => [
                'introduction' => 'Estimate profit or loss in pips and account currency from an entry price, an exit price, direction, and lot size.',
                'how_to_use' => 'Select the pair and buy or sell, then enter entry, exit, and lots. The result shows pip distance and an estimated cash P/L using a reference pip value.',
                'formula' => 'Pips = (exit − entry) ÷ pip size for a buy, or (entry − exit) ÷ pip size for a sell. Profit or loss ≈ pips × pip value for the lot size.',
                'example' => 'Buying 0.10 lot EUR/USD at 1.0800 and exiting at 1.0830 is +30 pips. At about $1 per pip on a mini lot, that is roughly $30 before costs.',
                'additional' => 'The figure does not subtract spread, commission, or swap. Use the Trading Cost Calculator if you want those costs in the same plan.',
                'faqs' => [
                    ['question' => 'Does this include trading costs?', 'answer' => 'No. It estimates price movement only. Spread, commission, and overnight swap are separate and should be subtracted from this result.'],
                    ['question' => 'Why can the cash amount differ from my platform?', 'answer' => 'Platforms use live quotes, exact contract sizes, and conversion rates. This tool uses reference rates for planning, not execution.'],
                    ['question' => 'Can I use it for short trades?', 'answer' => 'Yes. Choose sell so the pip distance is measured from a short entry to the exit price.'],
                ],
            ],
            'margin' => [
                'introduction' => 'Required margin is the deposit your broker locks when you open a position. It depends on notional size and leverage, not on how far price might move.',
                'how_to_use' => 'Enter the pair, lots, and leverage, then choose your account currency. Optional market price improves the notional estimate.',
                'formula' => 'Position value ≈ lots × contract size × price (converted to account currency). Required margin = position value ÷ leverage.',
                'example' => '1.00 lot EUR/USD near 1.0800 is about $108,000 notional. At 1:100 leverage, required margin is about $1,080.',
                'additional' => 'Free margin must also cover spread and floating loss. A stop-out can occur even when the initial margin looked comfortable.',
                'faqs' => [
                    ['question' => 'Is margin the same as risk?', 'answer' => 'No. Margin is collateral to keep the trade open. Risk is how much you can lose if price hits your stop or the position is closed.'],
                    ['question' => 'What happens if leverage is higher?', 'answer' => 'Higher leverage lowers the initial margin but increases the chance of a margin call if the market moves against you.'],
                    ['question' => 'Why does the pair matter?', 'answer' => 'Each pair has a different price and conversion into your account currency, so the same lot size can require different margin.'],
                ],
            ],
            'risk' => [
                'introduction' => 'Plan how much of the account you will risk on a trade and what reward you need for a given risk/reward ratio. This tool does not size lots; use it with the Position Size Calculator.',
                'how_to_use' => 'Enter balance, risk percent, and reward ratio (for example 2 for 1:2). The result shows cash risk, cash reward, and the win rate that breaks even over many trades.',
                'formula' => 'Risk amount = balance × (risk percent ÷ 100). Reward amount = risk amount × reward ratio. Break-even win rate = 100 ÷ (1 + reward ratio).',
                'example' => 'On $10,000 risking 1% at 1:2, you risk $100 to target $200. The break-even win rate is 33.3%, ignoring costs.',
                'additional' => 'Costs and losing streaks still matter. A plan that looks fine on paper can fail if the average loss is larger than the planned risk amount.',
                'faqs' => [
                    ['question' => 'What is a break-even win rate?', 'answer' => 'It is the percentage of winning trades you need, at this reward ratio, so that wins and losses cancel out before costs.'],
                    ['question' => 'Is 1% risk a rule?', 'answer' => 'It is a common starting point, not a requirement. Some traders use less. The important part is that the percentage is chosen in advance.'],
                    ['question' => 'How do I turn this into lots?', 'answer' => 'Take the risk amount from this page and enter it into the Position Size Calculator with your stop-loss in pips.'],
                ],
            ],
            'cost' => [
                'introduction' => 'Estimate the cost of a forex trade from spread, commission, and overnight swap. Use your broker’s published figures, or pick a BrokersCourt broker to pre-fill a parsed spread when one is available.',
                'how_to_use' => 'Select a pair and lot size. Enter spread in pips, commission per lot, swap per lot per night, and how many nights you expect to hold the trade. Leave a field blank if that cost is unknown — the calculator will mark it unavailable instead of inventing a number.',
                'formula' => 'Spread cost ≈ pip value × spread in pips. Commission cost = commission per lot × lots. Swap cost = swap per lot × lots × nights. Estimated total = the sum of the costs you actually entered.',
                'example' => 'If 1.00 lot EUR/USD has a $10 pip value, a 0.8-pip spread costs about $8. A $7 round-turn commission adds $7. Holding one night at −$3 swap brings the estimated total to $18.',
                'additional' => 'Published spreads are often “from” figures and can change with account type and session. BrokersCourt never invents missing commission or swap. Compare the text conditions on each broker profile before you trade.',
                'faqs' => [
                    ['question' => 'Why are some costs marked unavailable?', 'answer' => 'Many brokers publish spread as text and do not store a numeric swap on this site. If a value cannot be taken from your input or a parsed spread, it is shown as unavailable.'],
                    ['question' => 'Is the broker comparison a live price quote?', 'answer' => 'No. It shows each broker’s stored spread, commission, minimum deposit, and platforms from the BrokersCourt database, plus an estimated spread cost only when a numeric spread can be parsed.'],
                    ['question' => 'Does overnight swap always apply?', 'answer' => 'Swap usually applies when a position is held past the broker’s rollover time. Set nights to 0 for an intraday estimate.'],
                ],
            ],
            'pivot' => [
                'introduction' => 'Pivot points are support and resistance levels derived from the previous session’s high, low, and close. Traders use them as a map for the next session, not as a guarantee.',
                'how_to_use' => 'Enter yesterday’s high, low, and close, then choose Classic or Fibonacci. The calculator returns the pivot plus three resistance and three support levels.',
                'formula' => 'Classic pivot = (high + low + close) ÷ 3. Resistance and support are then built from the pivot and the prior range. Fibonacci pivots apply 38.2%, 61.8%, and 100% of that range.',
                'example' => 'If high is 1.0900, low is 1.0800, and close is 1.0860, the classic pivot is 1.0853. R1 and S1 sit above and below that pivot using the classic identities.',
                'additional' => 'Pivots work best as a planning overlay. Combine them with your own session context rather than treating a level as an automatic entry.',
                'faqs' => [
                    ['question' => 'Which session’s high, low, and close should I use?', 'answer' => 'Use the session you actually trade — daily is the most common. Inconsistent session times will shift every level.'],
                    ['question' => 'Classic or Fibonacci?', 'answer' => 'Classic is the standard floor-trader formula. Fibonacci spaces the same pivot with retracement ratios. Try the method that matches how you already mark levels.'],
                    ['question' => 'Are these the same as broker platform pivots?', 'answer' => 'They should match if the high, low, close, and method are the same. Platforms sometimes use a different session close.'],
                ],
            ],
            'fibonacci' => [
                'introduction' => 'Fibonacci retracement and extension levels mark possible pullback and target zones between a swing high and swing low.',
                'how_to_use' => 'Enter the swing high and low, then choose an uptrend (low to high) or downtrend (high to low). The calculator plots common ratios from 0% through 161.8%.',
                'formula' => 'Range = high − low. In an uptrend, level = low + (range × ratio). In a downtrend, level = high − (range × ratio).',
                'example' => 'A swing from 1.0700 to 1.0900 has a 200-pip range. The 61.8% retracement in an uptrend is 1.0700 + 123.6 pips ≈ 1.0824.',
                'additional' => 'These levels are geometric, not predictive. Many traders wait for price behaviour around a level rather than buying or selling the number alone.',
                'faqs' => [
                    ['question' => 'Which swing should I measure?', 'answer' => 'Use a clear swing that matches your timeframe. Mixing a weekly high with an hourly low produces levels that do not belong together.'],
                    ['question' => 'What do extension levels mean?', 'answer' => 'Ratios above 100% (127.2%, 161.8%) are often used as profit targets if price breaks the swing rather than only retracing it.'],
                    ['question' => 'Do I need both this and pivots?', 'answer' => 'They answer different questions. Pivots come from the last session’s range. Fibonacci comes from the swing you choose. You can use either or both.'],
                ],
            ],
            'converter' => [
                'introduction' => 'Convert an amount between major account and trading currencies using reference mid-market style rates. This is for planning, not for live execution.',
                'how_to_use' => 'Enter the amount, the currency you are converting from, and the currency you want. The result shows the reference rate and the converted amount.',
                'formula' => 'Both sides are expressed through USD using the reference table: converted = amount × (from→USD) ÷ (to→USD).',
                'example' => 'If EUR is stored at 1.08 USD and you convert 1,000 EUR to USD, the result is about 1,080 USD at the reference rate.',
                'additional' => 'Brokers and banks use their own bid/ask. Use this page to size an account or compare a result, then confirm the live rate on your platform.',
                'faqs' => [
                    ['question' => 'Are these live FX rates?', 'answer' => 'No. They are reference rates for education and planning. Live Market Widgets show market data from TradingView.'],
                    ['question' => 'Which currencies are supported?', 'answer' => 'The converter covers the major trading currencies in the reference table: USD, EUR, GBP, JPY, AUD, CAD, CHF, and NZD.'],
                    ['question' => 'Can I convert a profit from the Profit calculator?', 'answer' => 'Yes. Take the cash P/L in one currency and convert it here if your records use another currency.'],
                ],
            ],
            'live-markets' => [
                'introduction' => 'Watch live currency crosses, a forex heatmap, and the economic calendar in one place. The widgets are provided by TradingView for information only.',
                'how_to_use' => 'Use the tabs to switch between cross rates, the heatmap, and the calendar. Open a calculator from the sidebar when you want to turn a level or a news event into a position plan.',
                'formula' => '',
                'example' => '',
                'additional' => 'Heatmap colours and calendar times can differ from your broker’s feed. Verify the quote and the event time before you trade.',
                'faqs' => [
                    ['question' => 'Is this the same as my broker’s prices?', 'answer' => 'Not necessarily. TradingView widgets show market data from their sources. Your fill will follow your broker’s quote and spread.'],
                    ['question' => 'Where is the economic calendar?', 'answer' => 'Open the calendar tab on this page. It lists upcoming events that often move FX pairs.'],
                    ['question' => 'How do I go from a live rate to a trade plan?', 'answer' => 'Use the Pip, Position Size, or Trading Cost calculators linked from this page to turn a level into size and cost.'],
                ],
            ],
            default => [],
        };
    }
}
