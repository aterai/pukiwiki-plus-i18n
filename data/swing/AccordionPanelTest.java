//package example;
//-*- mode:java; encoding:utf8n; coding:utf-8 -*-
// vim:set fileencoding=utf-8:
//http://terai.xrea.jp/Swing/AccordionPanel.html
import java.awt.*;
import java.awt.event.*;
import java.util.*;
import java.util.List;
import javax.swing.*;
import javax.swing.border.*;

public class AccordionPanelTest{
    public JComponent makeUI() {
        Box accordion = Box.createVerticalBox();
        accordion.setBorder(BorderFactory.createEmptyBorder(10, 5, 5, 5));
        for(ExpansionPanel p: makeList()) {
            accordion.add(p);
            accordion.add(Box.createVerticalStrut(5));
        }
        accordion.add(Box.createVerticalGlue());

        JScrollPane scroll = new JScrollPane(JScrollPane.VERTICAL_SCROLLBAR_AS_NEEDED,
                                             JScrollPane.HORIZONTAL_SCROLLBAR_NEVER);
        scroll.getVerticalScrollBar().setUnitIncrement(25);
        scroll.getViewport().add(accordion);
        return scroll;
    }

    private List<ExpansionPanel> makeList() {
        return Arrays.asList(
            new ExpansionPanel("System Tasks") {
                @Override public JPanel makePanel() {
                    JPanel pnl = new JPanel(new GridLayout(0, 1));
                    JCheckBox c1 = new JCheckBox("aaaa");
                    JCheckBox c2 = new JCheckBox("aaaaaaa");
                    c1.setOpaque(false);
                    c2.setOpaque(false);
                    pnl.add(c1);
                    pnl.add(c2);
                    return pnl;
                }
            },
            new ExpansionPanel("Other Places") {
                @Override public JPanel makePanel() {
                    JPanel pnl = new JPanel(new GridLayout(0, 1));
                    pnl.add(new JLabel("Desktop"));
                    pnl.add(new JLabel("My Network Places"));
                    pnl.add(new JLabel("My Documents"));
                    pnl.add(new JLabel("Shared Documents"));
                    return pnl;
                }
            },
            new ExpansionPanel("Details") {
                @Override public JPanel makePanel() {
                    JPanel pnl = new JPanel(new GridLayout(0, 1));
                    ButtonGroup bg = new ButtonGroup();
                    JRadioButton b1 = new JRadioButton("aaa");
                    JRadioButton b2 = new JRadioButton("bbb");
                    JRadioButton b3 = new JRadioButton("ccc");
                    JRadioButton b4 = new JRadioButton("ddd");
                    for(JRadioButton b:Arrays.asList(b1,b2,b3,b4)) {
                        b.setOpaque(false); pnl.add(b); bg.add(b);
                    }
                    b1.setSelected(true);
                    return pnl;
                }
            }
        );
    }

    public static void main(String[] args) {
        EventQueue.invokeLater(new Runnable() {
            @Override public void run() {
                createAndShowGUI();
            }
        });
    }
    public static void createAndShowGUI() {
        JFrame frame = new JFrame();
        frame.setDefaultCloseOperation(WindowConstants.EXIT_ON_CLOSE);
        frame.getContentPane().add(new AccordionPanelTest().makeUI());
        frame.setSize(320, 240);
        frame.setLocationRelativeTo(null);
        frame.setVisible(true);
    }
}

//http://terai.xrea.jp/Swing/AccordionPanel.html
abstract class ExpansionPanel extends JPanel{
    abstract public JPanel makePanel();
    private final JButton label;
    private final JPanel panel;

    public ExpansionPanel(String title) {
        super(new BorderLayout());
        label = new JButton(title);
        label.addActionListener(new ActionListener() {
            @Override public void actionPerformed(ActionEvent e) {
                initPanel();
            }
        });
        add(label, BorderLayout.NORTH);

        panel = makePanel();
        panel.setVisible(false);
        add(panel);
    }
    @Override public Dimension getPreferredSize() {
        Dimension d = label.getPreferredSize();
        if(panel.isVisible()) {
            d.height += panel.getPreferredSize().height;
        }
        return d;
    }
    @Override public Dimension getMaximumSize() {
        Dimension d = getPreferredSize();
        d.width = Short.MAX_VALUE;
        return d;
    }
    protected void initPanel() {
        panel.setVisible(!panel.isVisible());
        revalidate();
        EventQueue.invokeLater(new Runnable() {
            @Override public void run() {
                panel.scrollRectToVisible(panel.getBounds());
            }
        });
    }
}
