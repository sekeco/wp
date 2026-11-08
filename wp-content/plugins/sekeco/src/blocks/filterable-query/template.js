/**
 * Shared portfolio Query Loop template.
 */
export const PORTFOLIO_QUERY_TEMPLATE = [
	[
		'core/query',
		{
			queryId: 103,
			query: {
				perPage: 100,
				pages: 0,
				offset: 0,
				postType: 'portfolio',
				order: 'desc',
				orderBy: 'date',
				author: '',
				search: '',
				exclude: [],
				sticky: '',
				inherit: false,
			},
			layout: { type: 'default' },
		},
		[
			[
				'core/post-template',
				{ layout: { type: 'grid', columnCount: 3 } },
				[
					[
						'core/group',
						{
							className: 'is-style-sekeco-card',
							style: {
								spacing: {
									blockGap: 'var:preset|spacing|20',
									padding: {
										top: 'var:preset|spacing|40',
										bottom: 'var:preset|spacing|40',
										left: 'var:preset|spacing|40',
										right: 'var:preset|spacing|40',
									},
								},
							},
							layout: { type: 'constrained' },
						},
						[
							[
								'core/post-featured-image',
								{
									isLink: true,
									aspectRatio: '4/3',
									style: {
										border: {
											radius: 'var:preset|border-radius|md',
										},
									},
								},
							],
							[
								'core/post-terms',
								{
									term: 'category',
									style: {
										typography: {
											fontSize:
												'var:preset|font-size|small',
										},
									},
								},
							],
							[
								'core/post-title',
								{
									isLink: true,
									level: 3,
									style: {
										typography: {
											fontSize:
												'var:preset|font-size|large',
											lineHeight: '1.2',
										},
									},
								},
							],
							[
								'core/post-excerpt',
								{
									excerptLength: 18,
									style: {
										typography: {
											fontSize:
												'var:preset|font-size|small',
										},
									},
								},
							],
						],
					],
				],
			],
		],
	],
];
